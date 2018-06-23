# ApiController

The Kairos API controller element

## 1) Subject

As the kairos projet API is based on the dispatching of the events between components to produce a sequential data process, the API controller is in charge of the request handling and dispatching result processing.

Based on the HTTP definition, the API controller have to be able to handle theses defined events:

 * Getting a list of items  with a GET method
 * Getting a specific item  with a GET method
 * Posting a specific item with a POST method
 * Replacing a specific item with a PUT method
 * Updating a specific item part with a PATCH method
 * Removing a specific item with a DELETE method

As defined by the REST specification, general understanding usage and needed capability for big services, the API controller has to be able to handle the specific overloaded POST. By its logic way, this overloaded method can be scripted over data processing workflow configuration and does not have to be handled by the controller directly.

In addition to the basics HTTP specification methods and their usage in REST API, the controller has to provide the ability to incorporate some advanced, self-designed or specification extension of the verbs. For example, the LINK and UNLINK methods can be used, in a user story of the library users and a way to inject these features inside the system have to be provided and correctly described.

To offer a valuable system, the logic has to assure the formatting of the output, whenever the processing failed. Then, to ensure this fail-safe operation, two level of event dispatching will be executed. The first thing will construct the data and return the result in the initial event, or throw an exception that will be injected with error code into this event. The second one will reconstruct the event result into a formatted output to be returned by the controller. In this second case, the result will have to be a valid response class or any data able to be handled by a system filter. This feature allows the final user to extend the framework, accordingly with its needs.

## 2) Class architecture

The API controller architecture has to follow the usage of frameworks and need for extendability of the advanced users. A specific consideration will be the default controller of existing frameworks and natural limitation of PHP language regarding the multiple inheritances.

Let's develop this specific study case in a simple example: as a developer, you want to use the API controller inside your project, and extend the feature included inside it. The first difficulty you'll face is the choice of integration to perform between the controller class and the framework system. If the framework provides an advanced controller, that fit your needs it'll become logic, even necessary to extend this controller in this implementation, and then get profits from your framework features.

If we assume that the API controller is a simple class or an abstract one you will face the single inheritance limitation. This comes from the fact it's not possible to extend your framework controller and the API controller at the same time. Hopefully, PHP language offers an alternative that resolves this specific issue by using trait system.

Then we will assume the trait system to be the place of implementation for the logic of the API controller. Then a controller will be and have to be part of a more complex system, it's mandatory to ensure the implementation interface and then use it in third party library or existing code parts. So to give guaranty on the interface implementation, a contract has to exist in the form of a PHP interface element and have to describe the input and output of each class methods.

## 3) Dependency description and use into the element

A the time of writing, the API controller is designed to have three production dependencies as:

 * psr/http-message
 * psr/log
 * symfony/event-dispatcher

### 3.1) psr/http-message

As defined by the PHP standard recommendation number 7, the HTTP messages like request and responses representations have to implement a specified interface. These interfaces are defined by the psr/http-message package and allow to use this library by any frameworks. By the way, at least the input of the API controller has to follow the PSR7 syntax.

### 3.2) psr/log

The first, and maybe the more important thing a piece of software has to take into account will be the security and investigation facility.

The part of investigation facility is a best practice commonly agreed by the development community, that allows receiving information about the internal software process.

About the security part, the OWASP top ten threat has to be taken into consideration.

As defined by the PHP standard recommendation number 3, the logger components have to implement a specified interface. This interface is defined by the psr/log package and allows to use this library by any frameworks.

### 3.3) symfony/event-dispatcher

The API controller system is designed to be easily extendable and will implement an event dispatching system, allowing the attachment and separation of logic by priority.

A draft for event management recommendation is on studying at the time of writing. But this draft state does not allow to integrate it immediately and we have to make a choice in term of technology implementation.

The Symfony event dispatcher is a good choice due to the understanding of current developers and offered features. Whenever we have to keep in mind the possibility to change it in next future.

## 4) Implementation specification

The API controller implements an execute method with two arguments, that receive the current request and the base event name. The name itself is used to differentiate the request behavior, as defined in chapter 1 of this documentation.

#### 4.1) Dependency injection specification

The instance will receive the logger instance and the event dispatcher at the instantiation directly in the constructor.

As a configuration level, the instance can also receive a process and a response prefix to be used during processing logic to compute the dispatched events. These prefix will be 'process_' and 'response_' by default, respectively for process and response events.

#### 4.2) execute method algorithm

```txt
Assuming request from parameters.
Assuming event name from parameters.
Assuming logger in properties.
Assuming event dispatcher in properties.

Computing process event name based on the parameter event name following the format '{processing_prefix}{event_name}'.

Log request method, computed event name and controller instance name at debug level.

Instantiating new process event and populate it with the parameter request.

Start fail-safe operation.
	Dispatching the process event over the stored event dispatcher with the computed process event name.
	In case of failure, hydrate the process event with the resulting exception.
End fail-safe operation.

Instantiating new response event, hydrated with the processing event response part. 

Computing response event name based on the parameter event name following the format '{response_suffix}{event_name}'.

Log computed event name at debug level.

Dispatching the response event over the stored event dispatcher with the computed response event name.

Returning the response event result.
```

#### 4.3) Event specification

In addition to the event specific logic, each event will have to be compliant with the base event dispatching library event. By the way, the event dispatching switch will be guaranteed by the initial event logic.

##### 4.3.1) Process event

The process event is in charge of the request and processing response shipping. So it will have to implement at least a 'getRequest', 'setResponse' and 'getResponse' methods. The original request itself will be provided with dependency inject at constructor level.

In addition, the process event will have to offer the ability to store and retrieve any parameters to be used during the process by the set of listeners attached to the dispatched event. A list of methods including 'setParameter', 'getParameter', 'setParameters', 'getParameters', 'hasParameter' will be implemented to interact with an internal named parameter bag.

##### 4.3.2) Response event

The response event is in charge of the response transformation, to be compliant with a given format. It will implement a 'setResponse' and a 'getResponse' methods, that allow changing the original response element.

## 5) Usage

#### 5.1) Basic usage

```PHP
use KairosProject\ApiController\Controller\ApiController;
use Symfony\Component\EventDispatcher\EventDispatcher;

// Instanciating event dispatcher
$eventDispatcher = new EventDispatcher();
$eventDispatcher->addListener('process_gets', $listener);
$eventDispatcher->addListener('response_gets', $listener);

$controller = new ApiController(
	$eventDispatcher,
	$logger
);

$response = $controller->execute($request, 'gets');
```

#### 5.2) Specific event names

```PHP
use KairosProject\ApiController\Controller\ApiController;
use Symfony\Component\EventDispatcher\EventDispatcher;

// Instanciating event dispatcher
$eventDispatcher = new EventDispatcher();
$eventDispatcher->addListener('specific_process_gets', $listener);
$eventDispatcher->addListener('specific_response_gets', $listener);

$controller = new ApiController(
    $eventDispatcher,
    $logger,
    'specific_process_',
    'specific_response_'
);

$response = $controller->execute($request, 'gets');
```

#### 5.3) Simple inheritance

```PHP
namespace My\Namespace;

use KairosProject\ApiController\Controller\ApiController;
use Psr\Http\Message\ServerRequestInterface;

class Controller extends ApiController
{
	public function execute(
		ServerRequestInterface $request,
		string $eventBaseName
	) {
		return parent::execute($request, $eventBaseName);
	}
}
```

#### 5.4) Advanced inheritance

```PHP
namespace My\Namespace;

use KairosProject\ApiController\Controller\ApiControllerInterface;
use KairosProject\ApiController\Trait\ExecutionTrait;

class Controller extends AnotherController implements ApiControllerInterface
{
    use ExecutionTrait;
}
```
