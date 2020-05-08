<?php
namespace KEINOS\Tests;

/**
 * @BeforeMethods({"init"})
 */
class FunctionHogeBench
{
    /**
     * @var int[]
     */
    private $numbers = [];

    /**
     * @var \SplMinHeap
     */
    private $splMinHeap;

    public function init()
    {
        // The SplMinHeap class provides the main functionalities of a heap,
        // keeping the minimum on the top.
        $this->splMinHeap = new \SplMinHeap();

        // Create random data
        for ($i=0; $i<1000; $i++) {
            $int = \random_int(-1000, 1000);
            $this->numbers[] = $int;
            $this->splMinHeap->insert($int);
        }
    }

    /**
     * @Warmup(2)
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchSplMinHeap(): void
    {
        // Add single random data
        $this->splMinHeap->insert(\random_int(-1000, 1000));

        // Get minimum number
        $this->splMinHeap->top();
    }

    /**
     * @Warmup(2)
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchNativeSort(): void
    {
        // Add single random data
        $this->numbers[] = \random_int(-1000, 1000);

        // Get minimum number
        sort($this->numbers);
        $this->numbers[0];
    }

    /**
     * @Warmup(2)
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchNativeForeach(): void
    {
        // Add single random data
        $this->numbers[] = \random_int(-1000, 1000);

        // Get minimum number
        $min = PHP_INT_MAX;
        foreach ($this->numbers as $number) {
            if ($number < $min) {
                $min = $number;
            }
        }
        $min;
    }

}
