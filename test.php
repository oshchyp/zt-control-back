<?php


abstract class A {

    public function A (){
        return $this->B();
    }

    abstract public function B ();

}

class B extends A {


    public function B()
    {
        // TODO: Implement B() method.
    }
}



$obj = new B();

