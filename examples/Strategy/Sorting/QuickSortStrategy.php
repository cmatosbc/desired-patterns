<?php

declare(strict_types=1);

namespace DesiredPatterns\Examples\Strategy\Sorting;

use DesiredPatterns\Strategy\AbstractStrategy;
use DesiredPatterns\Traits\ConfigurableStrategyTrait;

class QuickSortStrategy extends AbstractStrategy
{
    use ConfigurableStrategyTrait;
    
    protected array $requiredOptions = ['sort_key'];
    
    public function supports(array $data): bool
    {
        return count($data) > 1000; // Use QuickSort for larger datasets
    }
    
    public function validate(array $data): bool
    {
        if (empty($data)) {
            return false;
        }
        
        $sortKey = $this->getOption('sort_key');
        return isset($data[0][$sortKey]);
    }
    
    public function execute(array $data): array
    {
        $sortKey = $this->getOption('sort_key');
        return $this->quickSort($data, $sortKey);
    }
    
    private function quickSort(array $data, string $sortKey): array
    {
        if (count($data) <= 1) {
            return $data;
        }
        
        $pivot = $data[array_key_first($data)];
        $left = $right = [];
        
        for ($i = 1; $i < count($data); $i++) {
            if ($data[$i][$sortKey] < $pivot[$sortKey]) {
                $left[] = $data[$i];
            } else {
                $right[] = $data[$i];
            }
        }
        
        return array_merge(
            $this->quickSort($left, $sortKey),
            [$pivot],
            $this->quickSort($right, $sortKey)
        );
    }
}
