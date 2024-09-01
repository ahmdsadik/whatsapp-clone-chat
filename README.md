# WhatsApp Clone

This is a full-featured WhatsApp Clone built with Laravel and Pusher. The application replicates the core functionalities of WhatsApp, providing real-time chat features, user authentication, and more, all delivered through a RESTful API.

## Features

- **Real-Time Messaging**: Send and receive messages instantly using Laravel with Pusher.
- **User Authentication**: Secure login and registration using Laravel’s built-in authentication.
- **Chat API**: Endpoints for managing conversations, contacts, and messages.
- **Conversations and Contacts**: Create group chats, manage contacts, and interact in private conversations.
- **Conversations Permissions**: Owners and admins can control message-sending permissions for all users or restrict it to admins only.
- **Message Viewed Indicators**: Send a message and see if it was viewed or not.
- **Media Sharing**: Share images and files seamlessly via API.
- **Typing Indicators**: Real-time typing indicators using WebSockets.
- **Online Status**: API endpoints to view and manage online status.
- **Stories**: Users can share stories and control who can view them.
- **Linked Devices**: Users can link other devices to their accounts.
- **OTP Verification**: Users will receive an OTP to verify mobile number.

## Technologies Used

- **Laravel**: Backend framework for handling logic and API endpoints.
- **Pusher**: Service for real-time WebSockets functionality.
- **MySQL**: Database management.
- **Laravel Sanctum**: API authentication.
- **Twilio**: For Sending OTP to users.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
