<?php
/**
 * Created by PhpStorm.
 * User: apoltoratskyi
 * Date: 6/12/18
 * Time: 9:57 PM
 */
namespace Application\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class UserTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();

    }


    /**
     * @param CreateUser $user
     */
    public function saveUser(CreateUser $user)
    {
        $data = [
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
            'email'  => $user->email,
            'password' => $user->password,
        ];


        $email = $user->email;
        $rowset = $this->tableGateway->select(['email' => $email]);
        $dataFromDb = $rowset->current();

        if ($dataFromDb === null) {
            $this->tableGateway->insert($data);
            return;
        } else {
            throw new RuntimeException(sprintf(
                'Cannot save user with this email. Email %s already exists in DB.',
                $email
            ));
        }

    }
    /**
     * @param LoginUser $loginUser
     */
    public function loginUser(LoginUser $loginUser)
    {
        $data = [
            'email'  => $loginUser->email,
            'password' => $loginUser->password,
        ];

        $email = $loginUser->email;
        $rowset = $this->tableGateway->select(['email' => $email]);
        $dataFromDb = $rowset->current();

        if ($dataFromDb === null) {
            echo (sprintf('user with this email %s does not exists', $email));
        } else {
            echo (sprintf('welcome %s',$dataFromDb->firstName));
        }
    }


}