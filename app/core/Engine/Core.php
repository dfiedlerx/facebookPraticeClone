<?php /** @noinspection PhpUnhandledExceptionInspection */


namespace Core\Engine;


use ReflectionException;
use System\Models\Tools\Basic\Filter;
use ReflectionMethod;
use System\Models\Tools\Basic\StringC;


/**
 *
 * Classe que gerenciará o roteamento e leitura de URLs.
 * Ela procura respectivamente uma classe do tipo Controller que possua o mesmo nome e metodo dos parâmetros passados.
 * Também seta os valores adicionais caso sejam passadas.
 * Ex:
 *  www.site.com/user/new
 *  - Será procurado um controller de nome UserController e dentro dele o método new()
 *  www.site.com/user/new/param1/param2
 *  - O mesmo acima porém serão passados os parametros param1 e param2 no método new()
 *
 * Esta classe retornará o controller NotFoundController caso:
 *  - Não exista o controller ou action passados na url
 *  - Caso a quantidade de parametros adicionais sejam diferentes dos requeridos;
 *  - Caso os parametros passados sejam maiores que os parametros totais da action;
 *  - Caso a action retorne false por algum motivo.
 *
 * Class Core
 * @package Core\Engine
 * @author Daniel Fiedler
 */
class Core
{

    private $currentController;
    private $currentAction;
    private $urlParameters;
    private $methodParams = [];
    private $controllerExists = false;

    /**
     * Gerenciador do Core
     */
    public function run () {

        $this->trateUrl();
        $this->makeParametersArray();
        $this->setControllerAndAction();
        $this->callControllerAndAction();

    }

    /**
     * Remove domínio da url e remove parametros GET
     */
    private function trateUrl () {

        $this->urlParameters = str_replace
        (

            SYSTEM_DIRECTORY,
            '',
            Filter::internalFilter($_SERVER ['REQUEST_URI'], FILTER_SANITIZE_URL)

        );

        $this->urlParameters =
            StringC::removeAllAfterTerm('?', $this->urlParameters);

    }

    /**
     * Método que irá destrinchar a urlParameters e obtera o Controller e a action em um array.
     */
    private function makeParametersArray () {

        $this->urlParameters = explode('/', $this->urlParameters);

        //Caso o link padrão seja o diretório raiz do sistema
        if ($this->paramExistsAndIsEmpty(0)) {

            $this->removeFirstParameter();

        }

        //Converte para maiscula todas as primeiras letras de cada parâmetro
        foreach ($this->urlParameters as $paramKey => $currentParam) {

            $this->urlParameters[$paramKey] = ucfirst($currentParam);

        }

    }

    /**
     * Método que irá atribuir os valores corretos para $currentController e
     * $currentAction;
     *
     */
    private function setControllerAndAction () {

        $countParams = count($this->urlParameters);

        if ($countParams > 0 && !empty ($this->urlParameters[0])) {

            $this->tryToFindControllerAndAction($countParams);

        } else {

            $this->defaultController();
            $this->controllerExists = true;
            $this->defaultAction();

        }

    }

    private function tryToFindControllerAndAction (int $countParams) {

        $itCount = 1;
        $possibleAction = '';

        while ($itCount <= $countParams) {

            $possibleClassName = $this->urlParameters[$countParams - $itCount];
            $controllerNamespace = $this->getNamespaceController() . '\\' . $possibleClassName . CONTROLLERS_COMPLEMENT;

            if($this->ControllerExists($controllerNamespace)) {

                array_pop($this->methodParams);

            }

            /** @noinspection PhpMethodParametersCountMismatchInspection */
            if
            (
                $itCount > 1
                &&
                $this->ClassAndMethodExistsAndParamsAreValid($controllerNamespace, $possibleAction)
            ) {

                $this->currentController = $controllerNamespace;
                $this->currentAction = $possibleAction;
                $this->controllerExists = true;
                $this->methodParams = array_reverse($this->methodParams);
                break;

            } else if ($this->ClassAndMethodExistsAndParamsAreValid($controllerNamespace, DEFAULT_ACTION)) {

                $this->currentController = $controllerNamespace;
                $this->defaultAction();
                $this->controllerExists = true;
                break;

            } else {

                $this->methodParams[] = strtolower($possibleClassName);
                $possibleAction = strtolower($possibleClassName);
                $this->removeLastParameter();

            }

            $itCount += 1;

        }

    }

    private function getNamespaceController () : string {

        return CONTROLLERS_ROUTE . implode('\\', $this->urlParameters);

    }

    /**
     * Remove o primeiro parametro do array.
     * @return mixed
     */
    private function removeFirstParameter () {

        return array_shift($this->urlParameters);

    }

    /**
     * Remove o ultimo parâmetro do array
     * @return mixed
     */
    private function removeLastParameter () {

        return array_pop($this->urlParameters);

    }

    /**
     * Verifica se o elemento no indice passado existe e não é nulo ou uma string vazia
     * @param int $paramIndex
     * @return bool
     */
    private function paramExistsAndIsEmpty (int $paramIndex) : bool {

        return
            isset ($this->urlParameters[$paramIndex]) &&
            ($this->urlParameters[$paramIndex] == '' || is_null($this->urlParameters[$paramIndex]));

    }

    //Seta o Controller Padrão
    private function defaultController () {

        $this->currentController =
            CONTROLLERS_ROUTE . DEFAULT_CONTROLLER . '\\' .DEFAULT_CONTROLLER . CONTROLLERS_COMPLEMENT;

    }

    //Seta a action como padrão;
    private function defaultAction () {

        return $this->currentAction = DEFAULT_ACTION . ACTION_COMPLEMENT;

    }

    /**
     * Faz a chamada das classes correspondetes de controller e view.
     *
     */
    private function callControllerAndAction () {

        //Caso o Controller e a Action existam.
        if ($this->controllerExists) {

            $callController = new $this->currentController();

            if (!call_user_func_array (array($callController, $this->currentAction), $this->methodParams)) {

                $this->notFoundPage();

            }

        } else {

            $this->notFoundPage();

        }

    }

    /**
     * Verifica se o controller e a action existem e se os parâmetros adicionais condizem com a quantidade certa.
     * @param string $className
     * @param string $methodName
     * @return bool
     * @throws ReflectionException
     */
    private function ClassAndMethodExistsAndParamsAreValid (string $className, string $methodName) : bool {

        return method_exists($className, $methodName) && $this->validateNumberOfParamsAndPublicMethod($className, $methodName);

    }

    /**
     * @param string $className
     * @return bool
     */
    private function ControllerExists (string $className) : bool {

        return class_exists($className);

    }

    /**
     * Função que valida se o numero de argumentos passados é igual ao da action em questão.
     * É uma função totalmente maleavel e se adapta a qualquer action.
     *
     * @param string $className
     * @param string $methodName
     * @return bool
     * @throws ReflectionException
     */
    private function validateNumberOfParamsAndPublicMethod (string $className, string $methodName) : bool {

        $targetCallable = new ReflectionMethod ($className, $methodName);
        $numberOfUrlParameters = count($this->methodParams);

        //Caso o numero de parametros seja >= ao numero de parametros obrigatorios e <= ao numero de parametros no total
        return $numberOfUrlParameters >= $targetCallable->getNumberOfRequiredParameters()
               && 
               $numberOfUrlParameters <= $targetCallable->getNumberOfParameters()
               && $targetCallable->isPublic();

    }


    /**
     * Chama uma página informando que o conteudo não foi encontrado.
     * 
     */
    private function notFoundPage () : bool {

        $controllerConstant = CONTROLLERS_ROUTE . 'PageNotFound\PageNotFound' . CONTROLLERS_COMPLEMENT;

        /** @noinspection PhpUndefinedMethodInspection */
        return (new $controllerConstant())->index();
    
    }

}
