<?php

namespace App\Tests;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;

/**
 * Description of FormationTest
 *
 * @author aurin
 */
class FormationTest extends TestCase {
    
    public function testPublishedAtString() {
        $formation = new Formation();
        // date quelconque
        $formation->setPublishedAt(new \DateTime("2022-01-20"));
        $this->assertEquals("20/01/2022", $formation->getPublishedAtString());
        
        // date très ancienne et répétitive
        $formation->setPublishedAt(new \DateTime("1111-11-11"));
        $this->assertEquals("11/11/1111", $formation->getPublishedAtString());
        
        // date très lointaine dans le futur
        $formation->setPublishedAt(new \DateTime("9999-12-31"));
        $this->assertEquals("31/12/9999", $formation->getPublishedAtString());
    }
}
