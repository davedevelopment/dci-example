<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\Mink\Mink;
use Behat\MinkExtension\Context\MinkAwareInterface;

use Doctrine\DBAL\DriverManager;

use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class FeatureContext extends BehatContext implements MinkAwareInterface
{
    protected $mink;
    protected $minkParameters;
    protected $conn;

    protected $lastBookId;
    protected $lastUsername;
    protected $lastPassword;
    protected $lastUserId;

    public function __construct(array $parameters)
    {
        $connectionParams = array(
            'path' => $path = __DIR__ . '/../../data/app.db',
            'driver' => 'pdo_sqlite',
        );
        $this->conn = DriverManager::getConnection($connectionParams);

        // pretty sure this will get run more than necessary...
        $fixture = __DIR__.'/fixture.sql';
        exec("cat $fixture | sqlite3 $path"); // hackety hack
    }

    public function setMink(Mink $mink)
    {
        $this->mink = $mink;
    }

    public function setMinkParameters(array $parameters)
    {
        $this->minkParameters = $parameters;
    }

    /**
     * @Given /^I am a logged in user$/
     */
    public function iAmALoggedInUser()
    {
        $this->lastUsername = uniqid("user");
        $this->lastPassword = "dave";

        $encoder = new MessageDigestPasswordEncoder;
        $encodedPassword = $encoder->encodePassword($this->lastPassword, "");

        $this->conn->insert("user", array(
            "username" => $this->lastUsername,
            "password" => $encodedPassword,
            "salt" => "",
        ));

        $this->lastUserId = $this->conn->lastInsertId();
        $this->mink->getSession()->setBasicAuth($this->lastUsername, $this->lastPassword);
    }

    /**
     * @Given /^a book exists$/
     */
    public function aBookExists()
    {
        $this->conn->insert("book", array(
            "name" => "Dave's Book",
        ));

        $this->lastBookId = $this->conn->lastInsertId();
    }

    /**
     * @Given /^I am looking at the book\'s page$/
     */
    public function iAmLookingAtTheBookSPage()
    {
        $this->mink->getSession()->visit($this->url("/book/" . $this->lastBookId));
        $this->mink->assertSession()->statusCodeEquals(200);
    }

    /**
     * @When /^I click the add book to cart button$/
     */
    public function iClickTheAddBookToCartButton()
    {
        $page = $this->mink->getSession()->getPage();
        $page->find("css", "form input[type=submit]")->press();
        $this->mink->assertSession()->statusCodeEquals(200);
    }

    /**
     * @Then /^the book should be in my cart$/
     */
    public function theBookShouldBeInMyCart()
    {
        $books = $this->conn->fetchAll("SELECT book_id FROM user_book WHERE user_id = ?", array($this->lastUserId));

        foreach ($books as $book) {
            if ($book['book_id'] == $this->lastBookId) {
                return;
            }
        }

        throw new Exception("Book not found"); 
    }
    
    protected function url($path)
    {
        return $this->minkParameters['base_url'] . $path;
    }
}
