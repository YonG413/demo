#### laravel如何实现发送email完整例子

在 Laravel 中发送邮件非常简单，Laravel 提供了内置的 Mail 服务来实现这一功能。您只需要配置邮件驱动，并创建一个邮件类即可。下面是一个完整的例子，演示如何在 Laravel 中发送邮件：

1. 配置邮件驱动：
   打开 `config/mail.php` 文件，配置您的邮件驱动和相关设置。以下是一个使用 SMTP 驱动的示例配置：

```php
// config/mail.php

return [
    'driver' => env('MAIL_DRIVER', 'smtp'),
    'host' => env('MAIL_HOST', 'smtp.mailtrap.io'),
    'port' => env('MAIL_PORT', 2525),
    'username' => env('MAIL_USERNAME', 'your_mailtrap_username'),
    'password' => env('MAIL_PASSWORD', 'your_mailtrap_password'),
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
];
```

2. 创建邮件类：
   在 `app/Mail` 目录下，创建一个新的邮件类。例如，我们创建一个名为 `WelcomeMail` 的邮件类：

```php
// app/Mail/WelcomeMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.welcome')
                    ->subject('Welcome to our website!');
    }
}
```

3. 创建邮件视图：
   在 `resources/views/emails` 目录下，创建邮件视图。例如，我们创建一个名为 `welcome.blade.php` 的视图：

```blade
<!-- resources/views/emails/welcome.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Welcome Email</title>
</head>
<body>
    <h1>Welcome to our website!</h1>
    <p>Thank you for signing up. We are excited to have you on board.</p>
</body>
</html>
```

4. 发送邮件：
   现在，我们可以在控制器或任何地方发送邮件。以下是一个在控制器中发送欢迎邮件的示例：

```php
// app/Http/Controllers/WelcomeController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class WelcomeController extends Controller
{
    /**
     * Send the welcome email.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendWelcomeEmail()
    {
        $userEmail = 'user@example.com'; // Replace this with the user's email

        // Send the welcome email using the WelcomeMail class
        Mail::to($userEmail)->send(new WelcomeMail());

        return response('Welcome email sent successfully!');
    }
}
```

在上面的示例中，我们在 `WelcomeController` 中创建了一个方法 `sendWelcomeEmail`，用于发送欢迎邮件。我们使用 `Mail::to($userEmail)->send(new WelcomeMail())` 来发送邮件，其中 `$userEmail` 是收件人的邮箱地址。

这样，当调用 `sendWelcomeEmail` 方法时，Laravel 将会使用配置好的邮件驱动发送一封欢迎邮件给指定的收件人。

请确保在 `.env` 文件中配置正确的邮件驱动和邮件相关设置，例如设置邮件驱动为 `smtp`，以及配置对应的 SMTP 服务器、用户名、密码等信息。并且确保您的服务器能够发送邮件，以及邮件驱动对应的端口是否开放。