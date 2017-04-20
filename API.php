<?php

header('Content-Type: application/json');

$restApi = new API();
$restApi->CallMethod($_SERVER['REQUEST_METHOD'], explode("/", substr(@$_SERVER['PATH_INFO'], 1))[0]);
unset($restApi);

class API
{
    function getConnection()
    {
        return new mysqli('127.0.0.1', 'root', '', 'testdb');
    }

    function AddPerson($nume, $prenume, $varsta, $oras)
    {
        if (!$this->IsRequiredMethod('POST'))
        {
            return;
        }
        $con = null;
        $response = new stdClass();

        try
        {
            $con = $this->getConnection();
            $cmd = $con->prepare('INSERT INTO persoana(nume, prenume, varsta, oras) VALUES (?, ?, ?, ?)');
            $cmd->bind_param('ssis', $nume, $prenume, $varsta, $oras);
            $response->success = $cmd->execute();
            $response->data = $this->GetAllPersons();
        }
        catch (Exception $ex)
        {
            $response->success = false;
            $response->message = $ex->getMessage();
        }
        finally
        {
            if (!is_null($con))
            {
                $con->close();
            }
        }

        echo json_encode($response);
    }

    function UpdatePerson($id, $nume, $prenume, $varsta, $oras)
    {
        if (!$this->IsRequiredMethod('PUT'))
        {
            return;
        }
        $con = null;
        $response = new stdClass();

        try
        {
            $con = $this->getConnection();
            $cmd = $con->prepare('UPDATE persoana SET nume = ?, prenume = ?, varsta = ?, oras = ? WHERE id = ?');
            $cmd->bind_param('ssisi', $nume, $prenume, $varsta, $oras, $id);
            $response->success = $cmd->execute();
            $response->data = $this->GetAllPersons();
        }
        catch (Exception $ex)
        {
            $response->success = false;
            $response->message = $ex->getMessage();
            $response->data = null;
        }
        finally
        {
            if (!is_null($con))
            {
                $con->close();
            }
        }

        echo json_encode($response);
    }

    function IsRequiredMethod($method)
    {
        $response = new stdClass();
        if ($_SERVER['REQUEST_METHOD'] !== $method)
        {
            $response->success = false;
            $response->message = 'Method not supported';
            echo json_encode($response);
            return false;
        }
        return true;
    }

    function RemovePerson($id)
    {
        if (!$this->IsRequiredMethod('DELETE'))
        {
            return;
        }
        $con = null;
        $response = new stdClass();

        try
        {
            $con = $this->getConnection();
            $cmd = $con->prepare('DELETE FROM persoana WHERE id = ?');
            $cmd->bind_param('i', $id);
            $response->success = $cmd->execute();
            $response->data = $this->GetAllPersons();
        }
        catch (Exception $ex)
        {
            $response->success = false;
            $response->message = $ex->getMessage();
        }
        finally
        {
            if (!is_null($con))
            {
                $con->close();
            }
        }

        echo json_encode($response);
    }

    function GetPerson($id)
    {
        if (!$this->IsRequiredMethod('GET'))
        {
            return;
        }

        $con = null;
        $response = new stdClass();

        try
        {
            $con = $this->getConnection();
            $cmd = $con->prepare('SELECT nume, prenume, varsta, oras FROM persoana WHERE id = ?');
            $cmd->bind_param('i', $id);
            if ($cmd->execute())
            {
                $cmd->bind_result($response->data->nume, $response->data->prenume, $response->data->varsta, $response->data->oras);
                $cmd->fetch();
            }
            $response->success = true;
        }
        catch (Exception $ex)
        {
            $response->success = false;
            $response->data = null;
        }
        finally
        {
            if (!is_null($con))
            {
                $con->close();
            }
        }

        echo json_encode($response);
    }

    function GetPersons()
    {
        if (!$this->IsRequiredMethod('GET'))
        {
            return;
        }

        $response = new stdClass();
        $response->data = $this->GetAllPersons();
        $response->success = true;

        echo json_encode($response);
    }

    private function GetAllPersons()
    {
        $result = array();
        $con = null;

        try
        {
            $con = $this->getConnection();
            $dataSet = $con->query("SELECT nume, prenume, varsta, oras, id FROM persoana ORDER BY id DESC");
            while ($row = $dataSet->fetch_assoc())
            {
                $pers = new stdClass();
                $pers->nume = $row['nume'];
                $pers->prenume = $row['prenume'];
                $pers->varsta = $row['varsta'];
                $pers->oras = $row['oras'];
                $pers->id = $row['id'];
                $result[] = $pers;
            }
        }
        catch (Exception $ex)
        {
            $result = array();
        }
        finally
        {
            if (!is_null($con))
            {
                $con->close();
            }
        }

        return $result;
    }

    function getData($method)
    {
        $params = Array();
        switch ($method)
        {
            case 'GET':
                $params = $_GET;
                break;
            case 'POST':
                $params = $_POST;
                break;
            case 'PUT':
            case 'DELETE':
                parse_str(file_get_contents('php://input'), $params);
                break;
        }
        return $params;
    }

    public function CallMethod($method, $request)
    {
        $actionData = $this->getData($method);
        $args = array_values($actionData);
        //$this->$request('a', 'b' , '44', 't');
        //call_user_func_array('AddPerson', $args);
        //reflectionMethod = new ReflectionMethod('API', "$request");
        //echo $reflectionMethod->invoke(new API(), $args);
        //call_user_func_array("$request", $args);
        call_user_func_array(array($this,$request), $args);
    }
}




?>