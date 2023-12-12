# Laravel Email Orders

This is a simple Laravel project to simulate the processing of incoming order emails, listing them in an authenticated
route and allowing the admin to reply to an order.

## Requirements

- [Docker Desktop](https://docs.docker.com/engine/install/)

## Usage

### Setup

1. Clone the repository
2. Open the root directory of the cloned repository in a terminal
   - Run the following command to install dependencies and wait for it to complete:
    ```shell
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php82-composer:latest \
        composer install --ignore-platform-reqs
    ```
   - Run `cp .env.example .env` in the root directory (or manually duplicate the `.env.example` file and rename it)
   - Run `./vendor/bin/sail up` in the root directory
     - Check the [sail documentation](https://laravel.com/docs/10.x/sail) for more details or troubleshooting
     - This will keep the terminal busy, so you will need to open another terminal tab to run the next commands
   - Run `./vendor/bin/sail artisan migrate` in the root directory
3. Navigate to http://localhost in your browser
4. Tap the `Register` button in the top right corner
5. Create a user named `admin`, fill in the other details randomly (⚠️ username must be `admin` to reply orders)

### Testing
1. Once registered, tap the `Orders` navigation link at the top
2. Run `./vendor/bin/sail artisan mail:order` in the root dir to simulate receiving an order email
3. Navigate in a new tab to http://localhost:8025/ where you should see the mailpit UI
   - A new order email should be visible (created at step 2)
4. Navigate to http://localhost/orders or refresh the tab from step 1 to see the new order
5. Hovering with the mouse over an order will reveal a `Reply` button
6. Tap the `Reply` button, enter some text and tap `Submit`
   - You should navigate back to the orders list and see the order marked as `Replied`
   - The mailpit UI should show a reply email in the inbox with the text you entered at step 6
7. Repeat any times to retest the functionality.

## Architecture
The main architectural decision was to use events to decouple processing from the controllers.

Stubs for handling security concerns have been added to signal the intention of properly handling them, but
their implementation is in the most basic form and non-optimal for a real world scenario:
1. Basic auth for webhook endpoint: `HookBaseAuth`
2. Policy for order mutations: `OrderPolicy`

### Simulating order Emails

The code to simulate an order email is not polished since it's only for testing purposes. The command `mail:order`
setup in `console.php` leverages the mailing system of Laravel, sending an `OrderMailSimulation` which encapsulate
all the details of the order email.

### Processing Emails

The mailpit server is configured to call a webhook on each email received. The webhook is a Laravel route that
filters the order emails, then queues an event to process the email into an order. The event listener is responsible
for parsing the email and recording an order in the database with the extracted details from the email body.

Mailpit POST request → Webhook `MailHookController` → Event `OrderMailReceived` → Listener `ProcessOrderMail`

### Unit Tests

There are no unit tests to slightly reduce the time and effort put into the project.
The following list would have been a critical minimum set of unit tests to ensure the expected functionality:

1. `MailHookController`:
   - Should emit `OrderMailReceived` only when the email satisfies the condition of being an order email
   - Should not emit `OrderMailReceived` when the email is not an order email
   - Should pass the expected data to the event
2. `OrderMailReceived`:
   - Should be listened to by `ProcessOrderMail`
3. `ProcessOrderMail`:
   - Should create an order with the expected data
   - Should not create an order when the email is not an order email
   - Should not create an order when the email is already processed
4. `OrderController@update`
   - Should validate `reply` field
   - Should update the order with the reply
   - Should emit `OrderReplied` with the updated order data
5. `OrderReplied`:
   - Should be listened to by `SendOrderReplyMail`
6. `SendOrderReplyMail`:
   - Should send an email to the order author email address with the order reply

## Areas of Improvements
1. For simplicity there is no domain layer, because of this the `Order` model is used as a domain model, a DTO
    and an entity. This is not ideal, but it's a tradeoff to reduce the complexity of the project.
   - In a real world scenario there would be different models for each specific layer.
2. The `OrderController` is interacting with the database directly, in a proper architecture this logic should be
    moved to a repository or a use-case that interacts with a repository.
