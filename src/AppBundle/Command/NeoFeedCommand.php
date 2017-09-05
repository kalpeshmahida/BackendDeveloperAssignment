<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Neo\Client;
use Neo\FeedApi;
use AppBundle\Entity\Neo;

class NeoFeedCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('neo:feed')

            // the short description shown while running "php bin/console list"
            ->setDescription('Request the feed data from the nasa api')

            ->addArgument(
                'key',
                InputArgument::REQUIRED,
                'API-KEY'
            )
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command requests the feed data from nasa api and stores it in to local database");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @TODO: GuzzleHttp\Exception
         */
        $apiKey = $input->getArgument('key');
        $client = new Client($apiKey);
        $api = new FeedApi($client);

        // prepare start and end date
        $endDate = date_format(new \DateTime('now'), 'Y-m-d');
        $startDate = date_format(date_sub(new \DateTime('now'), date_interval_create_from_date_string('2 days')), 'Y-m-d');

        $data = $api->get($startDate, $endDate);

        // get entity manager object
        $em = $this->getContainer()->get('doctrine')->getManager();

        $nearEarthObjects = $data['near_earth_objects'];

        // extra information got from feed api but not in use for now
        // keeping for future reference use
        $links = $data['near_earth_objects'];
        $elementCount = $data['element_count'];

        foreach ($nearEarthObjects as $key => $item) {
            $date = $key;
            foreach ($item as $neoKey => $dateWiseNeo) {
                $reference = $dateWiseNeo['neo_reference_id'];
                $name = $dateWiseNeo['name'];
                $speed = $dateWiseNeo['close_approach_data'][0]['relative_velocity']['kilometers_per_hour'];
                $isHazardous = $dateWiseNeo['is_potentially_hazardous_asteroid'];

                $neo = new Neo();
                $neo->setDate(new \DateTime($date))
                    ->setReference($reference)
                    ->setName($name)
                    ->setSpeed($speed)
                    ->setIsHazardous($isHazardous);

                // persist neo data
                $em->persist($neo);
                // It is assumed data batch size is not big and for that reason not implementing bulk inserts using batch process mechanism of doctrine
                $em->flush();
            }
        }

    }
}