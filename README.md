Привет! Вот мое видение проектирования системы на 1-ую неделю

Ссылка для Miro, где отображается модель данных и доменов: 
Разобрал каждое требование на составляющие(кроме ReadOnly Model)

Бизнесс-цепочки

Авторизация - Procuder
Actor: Account
Command: Login
Data: Форма клюва
Event: Account.Logined

Создание таска - Procuder [ CUD ]
Actor: Account
Command: Create task
Data: Task, Account ID
Event: Task.Created

Заассайнить задачи: - Produer
Actor: Account with role admin or manager
Command: Reassign tasks
Data: ???
Event: Task.Reassigned

Отметить выполнение таска - Procuder [ CUD ]
Actor: Account
Command: Complete task
Data Task
Event: Task.Completed

Подсчитать заработанные деньги в конце дня и выплатить - Producer
Actor: CRON
Command: Calc billing
Data: ???
Event: Billing.Calced

Отправить отчет попугу на почту - Consumer
Actor: Billing.Calced
Command: Send notification
Data: Report, Email
Event: Notification.Created

Обнулить все средства в конце дня - Consumer [ CUD ]
Actor: Billing.Calced
Command: Set null for billing user
Data: Account
Event: Billing.Cleared

Создать запись в аудитлоге - Consumer [ CUD ]
Actor: Billing.Cleared
Command: Create audit log
Data: Account
Event: Audit.Created

Создание записи в аналитику - Consumer [ CUD ]
Actor: Task.Completed
Data: Task
Command: Create analytics record
Event: -

В системе, на мой взгляд, может быть несколько сервисов:
1) Auth
2) Task-Manager
3) Analytics
4) Notification
5) Billing
6) Audit-Logger

Все связи между сервисами могут быть асинхронными, кроме сервиса Auth - там связь синхронная в виду специфики запросов и кроме read-only models у сервисов

CUD-события:
1) Списывание денег или создание дебита Consumer/Produer
Actor: Task.Rassigned
Command: Create credit/debit
Data: Account public id
Event: Create/debit.Created

+ пометил в событиях выше, какие из них CUD
