<?php

namespace AppBundle\Tests\Game;

use AppBundle\Game\WordList;
use AppBundle\Game\Loader\LoaderInterface;

class WordListTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadDictionaries()
    {
        $path = '/path/to/words.txt';

        $loader = $this->getMock(LoaderInterface::class);
        $loader
            ->expects($this->once())
            ->method('load')
            ->with($path)
            ->willReturn([ 'argus' ])
        ;

        $wordList = new WordList();
        $wordList->addLoader('txt', $loader);
        $wordList->loadDictionaries([ $path ]);

        $words = self::readProperty('words', $wordList);

        $this->assertContains('argus', $words[5]);
    }

    /**
     * @expectedException \RuntimeException
     * @dataProvider provideDictionary
     */
    public function testUnsupportedDictionary($path)
    {
        $wordList = new WordList();
        $wordList->loadDictionaries([ $path ]);
    }

    public function provideDictionary()
    {
        return [
            [ '/path/to/fake.txt' ],
            [ '/path/to/fake.csv' ],
            [ '/path/to/fake.xml' ],
            [ '/path/to/fake.dat' ],
            [ '/path/to/fake.json' ],
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidWordLength()
    {
        $wordList = new WordList();
        $wordList->getRandomWord(7);
    }

    public function testGetRandomWord()
    {
        $wordList = new WordList();
        $wordList->addWord('ruby');
        $wordList->addWord('java');
        $wordList->addWord('toto');

        $found = [];
        while (3 !== count($found)) {
            $word = $wordList->getRandomWord(4);
            $found[$word] = $word;
        }

        $this->assertContains('ruby', $found);
        $this->assertContains('java', $found);
        $this->assertContains('toto', $found);
    }

    /**
     * Returns the value of a private/protected property of a given instance.
     *
     * @param string $property The property name to read
     * @param object $instance The instance to instrospect
     *
     * @return mixed
     */
    private static function readProperty($property, $instance)
    {
        if (!is_object($instance)) {
            throw new \InvalidArgumentException('$instance must be a valid object.');
        }

        $rc = new \ReflectionClass(get_class($instance));
        $rp = $rc->getProperty($property);
        $rp->setAccessible(true);

        return $rp->getValue($instance);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddInvalidWord()
    {
        $wordList = new WordList();
        $wordList->addWord(true);
    }

    public function testAddSingleWord()
    {
        $wordList = new WordList();
        $wordList->addWord('argus');

        $words = self::readProperty('words', $wordList);

        $this->assertArrayHasKey(5, $words);
        $this->assertCount(1, $words);
        $this->assertCount(1, $words[5]);
        $this->assertContains('argus', $words[5]);
    }

    public function testAddSameWordTwice()
    {
        $wordList = new WordList();
        $wordList->addWord('argus');
        $wordList->addWord('argus');

        $words = self::readProperty('words', $wordList);

        $this->assertArrayHasKey(5, $words);
        $this->assertCount(1, $words);
        $this->assertCount(1, $words[5]);
        $this->assertContains('argus', $words[5]);
    }

    public function testAddSeveralWords()
    {
        $wordList = new WordList();
        $wordList->addWord('argus');
        $wordList->addWord('ruby');
        $wordList->addWord('java');

        $words = self::readProperty('words', $wordList);

        $this->assertArrayHasKey(4, $words);
        $this->assertArrayHasKey(5, $words);
        $this->assertCount(2, $words);
        $this->assertCount(2, $words[4]);
        $this->assertCount(1, $words[5]);
        $this->assertContains('java', $words[4]);
        $this->assertContains('ruby', $words[4]);
        $this->assertContains('argus', $words[5]);
    }
}
