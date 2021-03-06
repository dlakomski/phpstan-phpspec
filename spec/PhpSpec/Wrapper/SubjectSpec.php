<?php

declare(strict_types=1);

namespace spec\PhpSpec\Wrapper;

use PhpSpec\CodeAnalysis\AccessInspector;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use PhpSpec\Wrapper\Wrapper;
use PhpSpec\Wrapper\Subject\WrappedObject;
use PhpSpec\Wrapper\Subject\Caller;
use PhpSpec\Wrapper\Subject\SubjectWithArrayAccess;
use PhpSpec\Wrapper\Subject\ExpectationFactory;
use PhpSpec\Wrapper\Subject\Expectation\Expectation;

class SubjectSpec extends ObjectBehavior
{
    public function let(
        Wrapper $wrapper,
        WrappedObject $wrappedObject,
        Caller $caller,
        SubjectWithArrayAccess $arrayAccess,
                 ExpectationFactory $expectationFactory,
        AccessInspector $accessInspector
    ) {
        $this->beConstructedWith(
            null,
            $wrapper,
            $wrappedObject,
            $caller,
            $arrayAccess,
            $expectationFactory,
            $accessInspector
        );
    }

    public function it_passes_the_created_subject_to_expectation(
        WrappedObject $wrappedObject,
        ExpectationFactory $expectationFactory,
        Expectation $expectation
    ) {
        $expectation->match(Argument::cetera())->willReturn(true);
        $wrappedObject->getClassName()->willReturn('spec\PhpSpec\Wrapper\Everything');
        $expectationFactory->create(Argument::cetera())->willReturn($expectation);

        $this->callOnWrappedObject('shouldBeAlright');
        $expectationFactory->create(Argument::any(), Argument::type('spec\PhpSpec\Wrapper\Everything'), Argument::any())->shouldHaveBeenCalled();
    }

    public function it_passes_the_existing_subject_to_expectation(
        Wrapper $wrapper,
        WrappedObject $wrappedObject,
        Caller $caller,
        SubjectWithArrayAccess $arrayAccess,
        ExpectationFactory $expectationFactory,
        Expectation $expectation
    ) {
        $existingSubject = new \ArrayObject();
        $this->beConstructedWith($existingSubject, $wrapper, $wrappedObject, $caller, $arrayAccess, $expectationFactory);

        $expectation->match(Argument::cetera())->willReturn(true);
        $wrappedObject->getClassName()->willReturn('\ArrayObject');
        $expectationFactory->create(Argument::cetera())->willReturn($expectation);

        $this->callOnWrappedObject('shouldBeAlright');
        $expectationFactory->create(Argument::any(), Argument::exact($existingSubject), Argument::any())->shouldHaveBeenCalled();
    }
}

class Everything
{
    public function isAlright()
    {
        return true;
    }
}
