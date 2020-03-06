<?php declare(strict_types=1);

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\{IP as IPEntity, Ips};
use AppBundle\Service\IpStorage as IpStorage;
use AppBundle\Form\{IpCreate, IPSearch as FormIpSearch};


class DefaultController extends Controller
{
    public function indexAction(Request $request, IpStorage $ip_service)
    {
        $searchEntity = new IPEntity();
        $searchForm = $this->createForm(FormIpSearch::class, $searchEntity);
        $searchForm->handleRequest($request);

        $ips = new Ips();
        $createEntity = new IPEntity();
        
        for ($i=0; $i<3; $i++) {
            $ips->getIps()->add(new IPEntity());
        }
        $createForm = $this->createForm(IpCreate::class, $ips, [
            'method' => 'POST',
        ]);
        $createForm->handleRequest($request);


        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            // $query = $searchForm->getData();
            $result = $ip_service->query($searchEntity->getIp());
            if ($result) {
                $this->addFlash('success', "Ip ".$searchEntity->getIp()." was found, number of adding = " . $result);
            } else {
                $this->addFlash('warning', "Ip ".$searchEntity->getIp()." NOT found");
            }
        }

        if ($createForm->isSubmitted() && $createForm->isValid()) {
            $list = $createForm->getData();
            foreach($list->getIps() as $ip) {
                $count = $ip_service->add($ip->getIp());
                $this->addFlash('success', "Ip was added, count = " . $count);
            }
        }

        return $this->render('default/index.html.twig', [
            'searchForm' => $searchForm->createView(),
            'createForm' => $createForm->createView(),
        ]);
    }
}
