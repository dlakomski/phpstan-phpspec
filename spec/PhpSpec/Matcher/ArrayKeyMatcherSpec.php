<?php

declare(strict_types=1);

namespace spec\PhpSpec\Matcher;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use PhpSpec\Formatter\Presenter\Presenter;

use ArrayObject;

class ArrayKeyMatcherSpec extends ObjectBehavior
{
    public function let(Presenter $presenter)
    {
        $presenter->presentValue(Argument::any())->willReturn('countable');
        $presenter->presentString(Argument::any())->willReturnArgument();

        $this->beConstructedWith($presenter);
    }

    public function it_is_a_matcher()
    {
        $this->shouldBeAnInstanceOf('PhpSpec\Matcher\Matcher');
    }

    public function it_responds_to_haveKey()
    {
        $this->supports('haveKey', [], [''])->shouldReturn(true);
    }

    public function it_matches_array_with_specified_key()
    {
        $this->shouldNotThrow()->duringPositiveMatch('haveKey', ['abc' => 123], ['abc']);
    }

    public function it_matches_array_with_specified_key_even_if_there_is_no_value()
    {
        $this->shouldNotThrow()->duringPositiveMatch('haveKey', ['abc' => null], ['abc']);
    }

    public function it_matches_ArrayObject_with_provided_offset(ArrayObject $array)
    {
        $array->offsetExists('abc')->willReturn(true);

        $this->shouldNotThrow()->duringPositiveMatch('haveKey', $array, ['abc']);
    }

    public function it_does_not_match_array_without_specified_key()
    {
        $this->shouldThrow()->duringPositiveMatch('haveKey', [1,2,3], ['abc']);
    }

    public function it_does_not_match_ArrayObject_without_provided_offset(ArrayObject $array)
    {
        $array->offsetExists('abc')->willReturn(false);

        $this->shouldThrow()->duringPositiveMatch('haveKey', $array, ['abc']);
    }

    public function it_matches_array_without_specified_key()
    {
        $this->shouldNotThrow()->duringNegativeMatch('haveKey', [1,2,3], ['abc']);
    }

    public function it_matches_ArrayObject_without_specified_offset(ArrayObject $array)
    {
        $array->offsetExists('abc')->willReturn(false);

        $this->shouldNotThrow()->duringNegativeMatch('haveKey', $array, ['abc']);
    }
}
