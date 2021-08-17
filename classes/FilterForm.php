<?php


class FilterForm implements ArrayAccess
{
    private int $maxRetailPrice = PHP_INT_MAX;
    private int $minWholesalePrice = 0;
    private string $typePrice = 'retail';
    private float $minPrice = 0;
    private float $maxPrice = PHP_INT_MAX;
    private string $moreOrless = 'all';
    private int $amount = 20;

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->{$offset});
    }

    public function offsetGet(mixed $offset): mixed
    {
        return isset($this->{$offset}) ? $this->{$offset} : null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $type = gettype($this->{$offset});

        switch ($type) {
            case 'integer':
                $this->{$offset} = intval($value);
                break;
            
            case 'double':
                $this->{$offset} = floatval($value);
                break;
            
            default:
                $this->{$offset} = $value;
                break;
        }

    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->{$offset});
    }

} 
?>