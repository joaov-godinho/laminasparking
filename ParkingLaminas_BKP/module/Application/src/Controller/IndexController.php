<?php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Db\Adapter\Adapter;
use Laminas\Http\Request;
use Laminas\Session\Container; 

class IndexController extends AbstractActionController
{
    protected $dbAdapter;

    public function __construct(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function indexAction()
    {
        $session = new Container('user');

        // Verifica se o usuário está logado e se é administrador
        if (!$session->offsetExists('userId') || $session->accessLevelId != 1) {
            return $this->redirect()->toRoute('login');
        }

        return new ViewModel();
    }
    public function parkingAction()
    {
        return new ViewModel();
    }
    public function reservaAction()
    {
        $adapter = $this->dbAdapter;
        $sql = 'SELECT spot_id, spot_number FROM parking_spots WHERE is_available = 1';
        $statement = $adapter->createStatement($sql);
        $parkingSpots = $statement->execute()->getResource()->fetchAll();

        $request = $this->getRequest();
        $message = '';

        // Verifica se o usuário está logado
        $session = new Container('user');
        if (!$session->offsetExists('userId')) {
            return $this->redirect()->toRoute('login');
        }
        $userId = $session->offsetGet('userId');
        $userPlanId = $session->offsetGet('planId');

        /** @var Request $request */
        if ($request->isPost()) {
            $postData = $request->getPost();
            $spotId = $postData['spot_id'];
            $checkInTime = $postData['check_in_time'];
            $checkOutTime = $postData['check_out_time'];

            // Calcula a diferença de horas entre check-in e check-out
            $checkInDateTime = new \DateTime($checkInTime);
            $checkOutDateTime = new \DateTime($checkOutTime);
            $interval = $checkInDateTime->diff($checkOutDateTime);
            $hours = $interval->h + ($interval->days * 24);

            // Determina a tarifa com base no plano do usuário
            if ($userPlanId == 1) {
                // Pré-pago
                $rate = 8.5;
            } else {
                // Pós-pago
                $rate = 10.0;
            }

            // Calcula o valor total
            $amount = $hours * $rate;

            $sql = 'INSERT INTO psusage (user_id, spot_id, check_in_time, check_out_time, amount) VALUES (?, ?, ?, ?, ?)';
            $statement = $adapter->createStatement($sql, [$userId, $spotId, $checkInTime, $checkOutTime, $amount]);

            try {
                $statement->execute();
                $updateSql = 'UPDATE parking_spots SET is_available = 0 WHERE spot_id = ?';
                $updateStatement = $adapter->createStatement($updateSql, [$spotId]);
                $updateStatement->execute();
                $message = 'Reserva realizada com sucesso!';
            } catch (\Exception $e) {
                $message = 'Erro ao realizar a reserva: ' . $e->getMessage();
            }
        }

        return new ViewModel([
            'parkingSpots' => $parkingSpots,
            'message' => $message,
        ]);
    }
    public function contatoAction()
    {
        return new ViewModel();
    }

    public function registerAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $postData = $request->getPost();
            $username = $postData['username'];
            $password = $postData['password'];
            $email = $postData['email'];
            $accessLevelId = 2;
            $planId = $postData['plan_id'];

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $adapter = $this->dbAdapter;
            $sql = 'INSERT INTO users (username, email, password_hash, access_level_id, plan_id) VALUES (?, ?, ?, ?, ?)';
            $statement = $adapter->createStatement($sql, [$username, $email, $hashedPassword, $accessLevelId, $planId]);

            try {
                $statement->execute();
                $message = 'Registro realizado com sucesso!';
            } catch (\Exception $e) {
                $message = 'Erro ao realizar o registro: ' . $e->getMessage();
            }

            return new ViewModel([
                'message' => $message,
            ]);
        }

        return new ViewModel();
    }

    public function loginAction()
    {
        $session = new Container('user');

        if ($session->offsetExists('userId')) {
            return $this->redirect()->toRoute('home');
        }

        $request = $this->getRequest();
        $message = '';

        /** @var Request $request */
        if ($request->isPost()) {
            $postData = $request->getPost();
            $username = $postData['username'];
            $password = $postData['password'];

            $sql = 'SELECT * FROM users WHERE username = ?';
            $statement = $this->dbAdapter->createStatement($sql, [$username]);
            $result = $statement->execute()->current();

            if ($result && password_verify($password, $result['password_hash'])) {
                $session->userId = $result['user_id'];
                $session->username = $result['username'];
                $session->accessLevelId = $result['access_level_id'];
                $session->planId = $result['plan_id'];

                return $this->redirect()->toRoute('home');
            } else {
                $message = 'Nome de usuário ou senha inválidos';
            }
        }
    }
    public function logoutAction()
    {
        $session = new Container('user');
        $session->getManager()->destroy();

        return $this->redirect()->toRoute('home');
    }
    public function userPanelAction()
    {
        $session = new Container('user');

        if (!$session->offsetExists('userId')) {
            // Se o usuário não está logado, redirecionar para a página de login
            return $this->redirect()->toRoute('login');
        }

        $request = $this->getRequest();
        $message = '';

        /** @var Request $request */
        if ($request->isPost()) {
            $postData = $request->getPost();
            $username = $postData['username'];
            $password = $postData['password'];

            // Atualizar as informações do usuário
            $adapter = $this->dbAdapter;
            $sql = 'UPDATE users SET username = ?, password_hash = ? WHERE user_id = ?';
            $statement = $adapter->createStatement($sql, [
                $username,
                password_hash($password, PASSWORD_DEFAULT),
                $session->userId
            ]);

            try {
                $statement->execute();
                $message = 'Informações atualizadas com sucesso!';
                $session->username = $username;
            } catch (\Exception $e) {
                $message = 'Erro ao atualizar as informações: ' . $e->getMessage();
            }
        }

        return new ViewModel([
            'username' => $session->username,
            'message' => $message,
        ]);
    }
    public function isAdmin()
    {
        $session = new Container('user');
        return $session->offsetExists('accessLevelId') && $session->accessLevelId == 1;
    }
    public function adminPanelAction()
    {
        if (!$this->isAdmin()) {
            return $this->redirect()->toRoute('home'); // Redirecionar para a página inicial se não for administrador
        }

        // Lógica para o painel de administração
        $adapter = $this->dbAdapter;
        $sql = 'SELECT * FROM users';
        $statement = $adapter->createStatement($sql);
        $users = $statement->execute()->getResource()->fetchAll();

        return new ViewModel([
            'users' => $users,
        ]);
    }
    

    public function editUserAction()
    {
        if (!$this->isAdmin()) {
            return $this->redirect()->toRoute('home');
        }

        $userId = (int) $this->params()->fromRoute('id', 0);
        if ($userId === 0) {
            return $this->redirect()->toRoute('admin-panel');
        }

        $adapter = $this->dbAdapter;
        $sql = 'SELECT * FROM users WHERE user_id = ?';
        $statement = $adapter->createStatement($sql, [$userId]);
        $user = $statement->execute()->current();

        if (!$user) {
            return $this->redirect()->toRoute('admin-panel');
        }

        $request = $this->getRequest();
        $message = '';

        /** @var Request $request */
        if ($request->isPost()) {
            $postData = $request->getPost();
            $username = $postData['username'];
            $email = $postData['email'];
            $accessLevelId = $postData['access_level_id'];

            $sql = 'UPDATE users SET username = ?, email = ?, access_level_id = ? WHERE user_id = ?';
            $statement = $adapter->createStatement($sql, [$username, $email, $accessLevelId, $userId]);

            try {
                $statement->execute();
                $message = 'Usuário atualizado com sucesso!';
            } catch (\Exception $e) {
                $message = 'Erro ao atualizar o usuário: ' . $e->getMessage();
            }
        }

        return new ViewModel([
            'user' => $user,
            'message' => $message,
        ]);
    }
    public function deleteUserAction()
    {
        if (!$this->isAdmin()) {
            return $this->redirect()->toRoute('home');
        }

        $userId = (int) $this->params()->fromRoute('id', 0);
        if ($userId === 0) {
            return $this->redirect()->toRoute('admin-panel');
        }

        $adapter = $this->dbAdapter;
        $sql = 'DELETE FROM users WHERE user_id = ?';
        $statement = $adapter->createStatement($sql, [$userId]);

        try {
            $statement->execute();
            $message = 'Usuário excluído com sucesso!';
        } catch (\Exception $e) {
            $message = 'Erro ao excluir o usuário: ' . $e->getMessage();
        }

        return $this->redirect()->toRoute('admin-panel');
    }
    public function reportsAction()
    {
        if (!$this->isAdmin()) {
            return $this->redirect()->toRoute('home'); // Redirecionar para a página inicial se não for administrador
        }

        $adapter = $this->dbAdapter;
        $sql = 'SELECT psusage.*, users.username, parking_spots.spot_number
                FROM psusage
                JOIN users ON psusage.user_id = users.user_id
                JOIN parking_spots ON psusage.spot_id = parking_spots.spot_id';
        $statement = $adapter->createStatement($sql);
        $reportData = $statement->execute()->getResource()->fetchAll();

        $sqlFinance = 'SELECT user_id, SUM(amount) as total_amount, plan_id FROM psusage GROUP BY user_id, plan_id';
        $statementFinance = $adapter->createStatement($sqlFinance);
        $financialReport = $statementFinance->execute()->getResource()->fetchAll();

        return new ViewModel([
            'reportData' => $reportData,
            'financialReport' => $financialReport,
        ]);
    }
    public function adminParkingSpotsAction()
    {
        if (!$this->isAdmin()) {
            return $this->redirect()->toRoute('home'); // Redirecionar para a página inicial se não for administrador
        }

        $adapter = $this->dbAdapter;
        $sql = 'SELECT * FROM parking_spots';
        $statement = $adapter->createStatement($sql);
        $parkingSpots = $statement->execute()->getResource()->fetchAll();

        return new ViewModel([
            'parkingSpots' => $parkingSpots,
        ]);
    }

    public function addParkingSpotAction()
    {
        if (!$this->isAdmin()) {
            return $this->redirect()->toRoute('home');
        }

        $request = $this->getRequest();
        $message = '';

        /** @var Request $request */
        if ($request->isPost()) {
            $postData = $request->getPost();
            $spotNumber = $postData['spot_number'];

            $adapter = $this->dbAdapter;
            $sql = 'INSERT INTO parking_spots (spot_number, is_available) VALUES (?, 1)';
            $statement = $adapter->createStatement($sql, [$spotNumber]);

            try {
                $statement->execute();
                $message = 'Vaga adicionada com sucesso!';
            } catch (\Exception $e) {
                $message = 'Erro ao adicionar a vaga: ' . $e->getMessage();
            }

            return new ViewModel([
                'message' => $message,
            ]);
        }

        return new ViewModel();
    }

    public function editParkingSpotAction()
    {
        if (!$this->isAdmin()) {
            return $this->redirect()->toRoute('home');
        }

        $spotId = (int) $this->params()->fromRoute('id', 0);
        if ($spotId === 0) {
            return $this->redirect()->toRoute('admin-parking-spots');
        }

        $adapter = $this->dbAdapter;
        $sql = 'SELECT * FROM parking_spots WHERE spot_id = ?';
        $statement = $adapter->createStatement($sql, [$spotId]);
        $parkingSpot = $statement->execute()->current();

        if (!$parkingSpot) {
            return $this->redirect()->toRoute('admin-parking-spots');
        }

        $request = $this->getRequest();
        $message = '';

        /** @var Request $request */
        if ($request->isPost()) {
            $postData = $request->getPost();
            $spotNumber = $postData['spot_number'];
            $isAvailable = $postData['is_available'];

            $sql = 'UPDATE parking_spots SET spot_number = ?, is_available = ? WHERE spot_id = ?';
            $statement = $adapter->createStatement($sql, [$spotNumber, $isAvailable, $spotId]);

            try {
                $statement->execute();
                $message = 'Vaga atualizada com sucesso!';
            } catch (\Exception $e) {
                $message = 'Erro ao atualizar a vaga: ' . $e->getMessage();
            }
        }

        return new ViewModel([
            'parkingSpot' => $parkingSpot,
            'message' => $message,
        ]);
    }

    public function deleteParkingSpotAction()
    {
        if (!$this->isAdmin()) {
            return $this->redirect()->toRoute('home');
        }

        $spotId = (int) $this->params()->fromRoute('id', 0);
        if ($spotId === 0) {
            return $this->redirect()->toRoute('admin-parking-spots');
        }

        $adapter = $this->dbAdapter;
        $sql = 'DELETE FROM parking_spots WHERE spot_id = ?';
        $statement = $adapter->createStatement($sql, [$spotId]);

        try {
            $statement->execute();
            $message = 'Vaga excluída com sucesso!';
        } catch (\Exception $e) {
            $message = 'Erro ao excluir a vaga: ' . $e->getMessage();
        }

        return $this->redirect()->toRoute('admin-parking-spots');
    }
}
