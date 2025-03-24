<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CurrencyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CurrencyController extends AbstractController
{
    public function __construct(private CurrencyRepository $currencyRepository)
    {
    }


    #[Route('/{_locale}/exchangeXtoPLN', name: 'exchangeXtoPLN')]
    #[IsGranted('ROLE_USER')]
    public function exchangeXtoPLN(): Response
    {
        $currenciesName = $this->currencyRepository->findAllCurrenciesName();
        return $this->render('exchangeMoney/exchangeXtoPLN.html.twig', [
            'currenciesName' => $currenciesName
        ]);
    }

    #[Route('/{_locale}/exchangePLNtoX', name: 'exchangePLNtoX')]
    #[IsGranted('ROLE_USER')]
    public function exchangePLNtoX(): Response
    {
        $currenciesName = $this->currencyRepository->findAllCurrenciesName();
        return $this->render('exchangeMoney/exchangePLNtoX.html.twig', [
            'currenciesName' => $currenciesName
        ]);
    }

    #[Route('/{_locale}/currencySellRate', name: 'currencySellRate', methods: ['GET'])]
    public function getCurrencySellRate(Request $request): JsonResponse
    {
        $currency = $request->get('currency');
        if (!$currency) {
            return $this->json(['error' => 'Brak waluty'], 400);
        }
        $currencyByName = $this->currencyRepository->findOneBy(['name' => $currency]);
        if (!$currencyByName) {
            return $this->json(['error' => 'Waluta nie znaleziona'], 404);
        }

        return $this->json([
            'currency' => $currencyByName->getName(),
            'sellRate' => $currencyByName->getSellRate(),
        ]);
    }

    #[Route('/{_locale}/currencyBuyRate', name: 'currencyBuyRate', methods: ['GET'])]
    public function getCurrencyBuyRate(Request $request): JsonResponse
    {
        $currency = $request->get('currency');
        if (!$currency) {
            return $this->json(['error' => 'Brak waluty'], 400);
        }
        $currencyByName = $this->currencyRepository->findOneBy(['name' => $currency]);
        if (!$currencyByName) {
            return $this->json(['error' => 'Waluta nie znaleziona'], 404);
        }
        return $this->json([
            'currency' => $currencyByName->getName(),
            'buyRate' => $currencyByName->getBuyRate(),
        ]);
    }
}