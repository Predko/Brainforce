<?php

// Класс, содержащий данные фильтра.
class FilterForm implements ArrayAccess
{
    private int $maxRetailPrice = PHP_INT_MAX;
    private int $minWholesalePrice = 0;
    private string $typePrice = 'retail';
    private float $minPrice = 0;
    private float $maxPrice = PHP_INT_MAX;
    private string $moreOrless = 'all';
    private int $amount = 20;

    private function Set_maxRetailPrice($val) { $this->maxRetailPrice = intval($val); }

    private function Set_minWholesalePrice($val) { $this->minWholesalePrice = intval($val); }
    private function Set_typePrice($val) { $this->typePrice = $val; }
    
    private function Set_minPrice($val) 
    { 
        $res = GetValidPrice($val);

        if ($res > $this->maxPrice)
        {
            $res = $this->maxPrice;
        }

        $this->minPrice = $res; 
    }
    
    private function Set_maxPrice($val) 
    { 
        $res = GetValidPrice($val);

        if ($res < $this->minPrice)
        {
            $res = $this->minPrice;
        }

        $this->maxPrice = $res; 
    }

    private function GetValidPrice($var)
    {
        $res = floatval($val);

        if ($res < $this->minWholesalePrice)
        {
            $res = $this->minWholesalePrice;
        }
        else
        if ($res > $this->maxRetailPrice)
        {
            $res = $this->maxRetailPrice;
        }

        return $res;
    }
    
    private function Set_moreOrless($val) { $this->moreOrless = $val; }
    private function Set_amount($val) 
    { 
        $res = intval($val);

        if ($res < 0)
        {
            $res = 0;
        }
        else
        if ($res > 999)
        {
            $res = 999;
        }

        $this->amount = $res;
    }

    // Реализация ArrayAccess.
    public function offsetExists($offset): bool
    {
        return isset($this->{$offset});
    }

    public function offsetGet($offset): string
    {
        return isset($this->{$offset}) ? $this->{$offset} : null;
    }

    public function offsetSet($offset, $value): void
    {
        $this->{"Set_".$offset}($value);
    }

    public function offsetUnset($offset): void
    {
        unset($this->{$offset});
    }

} 
?>