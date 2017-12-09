<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Admin
 *
 * @ORM\Table(name="admin")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\AdminRepository")
 */
class Admin extends BaseEntity
{

	public function __construct($email, $password)
	{
		$this->email = $email;
		$this->password = $password;
		$this->salt = base64_encode(random_bytes(48));
	}

	/**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

	/**
	 * @ORM\Column(name="email", type="string", length=100)
	 */
    private $email;

	/**
	 * Get email
	 *
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	public function setEmail($mail) {
		$this->email = $mail;
	}

	/**
	 * @ORM\Column(name="password", type="string", length=100)
	 */
	private $password;

	/**
	 * Get password
	 *
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	public function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * @ORM\Column(name="salt", type="string", length=100)
	 */
	private $salt;

	/**
	 * Get salt
	 *
	 * @return string
	 */
	public function getSalt() {
		return $this->salt;
	}
}

