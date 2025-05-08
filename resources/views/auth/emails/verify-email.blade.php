<!-- resources/views/emails/verify.blade.php -->
<h1>Verify Your Email</h1>
<p>Click the button below to verify your email address:</p>

<a href="{{ $verificationUrl }}" 
   style="background: #3490dc; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
   Verify Email
</a>