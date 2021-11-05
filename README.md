# Задание №2:

**Условие:**

Необходимо реализовать клиент для абстрактного (вымышленного) сервиса комментариев "example.com". 

Проект должен представлять класс или набор классов, который будет делать http запросы к серверу. На выходе должна получиться библиотека, который можно будет подключить через composer к любому другому проекту.

У этого сервиса есть 3 метода:

+ GET http://example.com/comments - возвращает список комментариев
+ POST http://example.com/comment - добавить комментарий.
+ PUT http://example.com/comment/{id} - по идентификатору комментария обновляет поля, которые были в в запросе

Объект comment содержит поля:

+ id - тип int. Не нужно указывать при добавлении.
+ name - тип string.
+ text - тип string.

Написать phpunit тесты, на которых будет проверяться работоспособность клиента.
Сервер example.com писать не надо! Только библиотеку для работы с ним.

## Решение задания

Библиотека опубликована на https://packagist.org/

**Установка**

```sh
composer require aik27/drom-client
```

**Применение**

```php
use aik27\DromClient\Client;
use aik27\DromClient\Config;
use aik27\DromClient\Scenario;
use aik27\DromClient\Http\GuzzleClient;
use aik27\DromClient\Validators\JsonValidator;

try {

    /* 
     * Конфигурирование клиента под условия тестового задания.
     */

    $config = new Config([
    
        /* 
         * Список целевых адресов.
         * В данном случае, для сервиса комментариев.
         */
         
        'urlGetAll' => 'http://example.com/comments',
        'urlCreate' => 'http://example.com/comment',
        'urlUpdate' => 'http://example.com/comment/{id}',
        
        /* 
         * Внедрение зависимостей.
         * Для HTTP клиента можно использовать адаптеры GuzzleClient() или SymfonyClient()
         * Для валидации ответа сервера JsonValidator() или XmlValidator()
         * Использование валидатора ответа не является обязательным.
         */

        'httpClient' => new GuzzleClient(),
        'validator' => new JsonValidator(),
        
        /* 
         * Сценарии валидации данных на стороне клиента перед отправкой.
         * Реализуются через экземпляры класса Scenario. 
         * Использование сценариев не является обязательным.
         * 
         * Доступные типы параметров валидации:
         * 
         *  type - int|string - проверка типа значения [обязательный]
         *  required - true|false - является ли поле обязательным 
         */

        'scenarioCreate' => new Scenario([
            'name' => [
                'type' => 'string',
                'required' => true,
            ],
            'text' => [
                'type' => 'string',
                'required' => true,
            ],
        ]),
        'scenarioUpdate' => new Scenario([
            'id' => [
                'type' => 'int',
                'required' => true,
            ],
            'name' => [
                'type' => 'string',
                'required' => false,
            ],
            'text' => [
                'type' => 'string',
                'required' => false,
            ],
        ]),
    ]);

    $client = new Client($config);
    
    # Возвращает список записей
    $client->getAll();
    
    # Добавляет запись
    $client->create((object)[
        'name' => 'Alexandr',
        'text' => 'Hello world',
    ]);
    
    # Редактирует запись по идентификатору. Можно указать только нужные поля 
    $client->update((object)[
        'id' => 42,
        'text' => 'New text',
    ]);
} catch (\Exception $e) {
    echo $e->getMessage();
}

```

**PHPUnit тесты**

Написано 26 тестов покрывающие различные варианты конфигурации, сценарии валидации и запросы.

```sh
phpunit tests
```