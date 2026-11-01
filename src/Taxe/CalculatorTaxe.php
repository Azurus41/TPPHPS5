<?php

namespace App\Taxe;

class CalculatorTaxe
{
    private const TAUX_TVA = 0.20;

    public function calculerTVA(float $prixHT): float
    {
        return $prixHT * self::TAUX_TVA;
    }

    public function calculerTTC(float $prixHT): float
    {
        return $prixHT + $this->calculerTVA($prixHT);
    }
}
