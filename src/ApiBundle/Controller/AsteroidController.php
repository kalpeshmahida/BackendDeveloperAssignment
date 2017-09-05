<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Util\Codes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;

class AsteroidController extends FOSRestController
{
    /**
     * @Route("/hazardous")
     * @Method("GET")
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Retrieve all DB entries which contain potentially hazardous asteroids",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     * @param ParamFetcher $paramFetcher
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing hazardous NEOs.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="50", description="How many NEOs to return.")
     *
     * @Annotations\View(templateVar="hazardous")
     *
     *
     * @return View
     */
    public function hazardousAction(ParamFetcher $paramFetcher)
    {
        /*
         * @TODO: It's better have paginated data here
         */
        $offset = $paramFetcher->get('offset');
        $start = null == $offset ? 0 : $offset + 1;
        $limit = $paramFetcher->get('limit');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("AppBundle:Neo");
        $neos = $repository->findAll();

        return array('data' => $neos);
    }

    /**
     * @Route("/fastest")
     * @Method("GET")
     *
     * Returns details of the fastest asteroid.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Retrieve details of the fastest asteroid",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     * @param ParamFetcher $paramFetcher
     *
     * @Annotations\QueryParam(name="hazardous", strict=true, description="is hazardous(true/false)?", default="false")
     *
     * @Annotations\View(templateVar="fastest_neo")
     *
     * @return View
     */
    public function fastestAction(ParamFetcher $paramFetcher)
    {
        $isHazardous = ('true' === $paramFetcher->get('hazardous')) ? true : false;

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("AppBundle:Neo");
        $result = $repository->findFastestAsteroid($isHazardous);

        return $result;
    }

    /**
     * @Route("/best-year")
     * @Method("GET")
     *
     * Returns return a year with most asteroids.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Retrieve a year with most asteroids",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     * @param ParamFetcher $paramFetcher
     *
     * @Annotations\QueryParam(name="hazardous", strict=true, description="is hazardous(true/false)?", default="false")
     *
     * @return View
     */
    public function bestYearAction(ParamFetcher $paramFetcher)
    {
        $isHazardous = ('true' === $paramFetcher->get('hazardous')) ? true : false;

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("AppBundle:Neo");
        $result = $repository->findYearWithMostAsteroid($isHazardous);

        return $result;
    }

    /**
     * @Route("/best-month")
     * @Method("GET")
     *
     * Returns return the month with most asteroids in history.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Retrieve the month with most asteroids in history",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     * @param ParamFetcher $paramFetcher
     *
     * @Annotations\QueryParam(name="hazardous", strict=true, description="is hazardous(true/false)?", default="false")
     *
     * @return View
     */
    public function bestMonthAction(ParamFetcher $paramFetcher)
    {
        $isHazardous = ('true' === $paramFetcher->get('hazardous')) ? true : false;

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("AppBundle:Neo");
        $result = $repository->findMonthWithMostAsteroid($isHazardous);

        return $result;
    }
}
