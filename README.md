# 2 неделя

Привет! При реализации выбрал концепцию очереди событий, а реализацию - **RabbitMQ**. Хотел поработать, но вот никак не доходило до этого. В следующих **pet-project** обязательно попробую Kafka и лог сообщений

К сожалению, ТЗ на это ДЗ я до конца еще не успел реализовать из-за исключительно проблемы с OAuth, решил взять этот подход для практики и пока курю почему оно не пашет как надо.

На данный момент мои два сервиса общаются, от **Auth-сервиса** исходит 3 CUD-события:
- [Удаление/обновление](https://github.com/fman42/UberPopugInc/blob/main/Auth/app/Http/Controllers/HomeController.php) 
- [Создание пользователя](https://github.com/fman42/UberPopugInc/blob/main/Auth/app/Http/Controllers/Auth/RegisterController.php) - 78 строка

**TaskTracker-сервис** является и консьюером, и продюсером

От него исходит [1-CUD событие](https://github.com/fman42/UberPopugInc/blob/main/TaskTracker/app/Http/Controllers/TaskController.php) - строка 30

И помимо [2 бизнесс-события](https://github.com/fman42/UberPopugInc/blob/main/TaskTracker/app/Http/Controllers/TaskController.php) - строка 41 и 52

Консьюмит сообщения [здесь](https://github.com/fman42/UberPopugInc/blob/main/TaskTracker/app/Jobs/Consumer.php)


https://www.cloudamqp.com/ дает бесплатные ресурсы для разработчиков, так что я RabbitMQ оставил в облаке(если это нарушение ДЗ, то разверну локально)
