<?php

namespace App\Controller;

use App\Entity\Currencies;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CurrencyController extends AbstractController
{
    #[Route('/addCurrency', name: 'add_currency')]
    public function addCurrencies(EntityManagerInterface $entityManager) : Response
    {
        $currenciesData=[
            ['name'=>'EUR','buyRate'=>4.107,'sellRate'=>4.190],
            ['name'=>'CZK','buyRate'=>0.164,'sellRate'=>0.167],
            ['name'=>'AUD','buyRate'=>2.450,'sellRate'=>2.499],
            ['name'=>'USD','buyRate'=>3.945,'sellRate'=>4.025],
            ['name'=>'CHF','buyRate'=>4.372,'sellRate'=>4.460],
        ];

        foreach($currenciesData as $currencyData){
            $currency= new Currencies();
            $currency->setName($currencyData['name']);
            $currency->setBuyRate($currencyData['buyRate']);
            $currency->setSellRate($currencyData['sellRate']);
            $rateDifference = round($currencyData['sellRate'] - $currencyData['buyRate'],3);
            $currency->setSpread($rateDifference);
            $entityManager->persist($currency);
        }
        $entityManager->flush();
        return new Response('Waluty zostały dodane z obliczoną różnicą!');
    }

    #[Route('/exchangeXtoPLN', name: 'exchangeXtoPLN')]
    public function exchangeXtoPLN(CurrencyRepository $currencyRepository): Response
    {
        $currenciesName=$currencyRepository->findAllCurrenciesName();
        return $this->render('exchangeMoney/exchangeXtoPLN.html.twig',[
            'currenciesName'=>$currenciesName]);
    }

    #[Route('/exchangePLNtoX', name: 'exchangePLNtoX')]
    public function exchangePLNtoX(CurrencyRepository $currencyRepository): Response
    {
        $currenciesName=$currencyRepository->findAllCurrenciesName();
        return $this->render('exchangeMoney/exchangePLNtoX.html.twig',[
            'currenciesName'=>$currenciesName]);
    }

    #[Route('/currencySellRate', name: 'currencySellRate', methods: ['GET'])]
    public function getCurrencySellRate(Request $request, CurrencyRepository $currencyRepository) : JsonResponse
    {
       $currency=$request->get('currency');
       if(!$currency){
           return $this->json(['error' => 'Brak waluty'],400);
       }
       $currencyByName=$currencyRepository->findOneBy(['name'=>$currency]);
       if(!$currencyByName){
           return $this->json(['error' => 'Waluta nie znaleziona'],404);
       }

       return $this->json([
           'currency' => $currencyByName->getName(),
           'sellRate'=>$currencyByName->getSellRate(),
           ]);
    }

    #[Route('/currencyBuyRate', name: 'currencyBuyRate', methods: ['GET'])]
    public function getCurrencyBuyRate(Request $request, CurrencyRepository $currencyRepository) : JsonResponse
    {
        $currency=$request->get('currency');
        if(!$currency){
            return $this->json(['error' => 'Brak waluty'],400);
        }
        $currencyByName=$currencyRepository->findOneBy(['name'=>$currency]);
        if(!$currencyByName){
            return $this->json(['error' => 'Waluta nie znaleziona'],404);
        }
        return $this->json([
            'currency' => $currencyByName->getName(),
            'buyRate' => $currencyByName->getBuyRate(),
        ]);
    }
}