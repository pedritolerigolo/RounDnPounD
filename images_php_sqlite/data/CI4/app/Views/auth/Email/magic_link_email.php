<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation de votre mot de passe</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd;">
        <h2 style="color: #333;">Bonjour,</h2>
        
        <p>Vous avez demandé un lien pour la connexion car vous avez oublié votre mot de passe.</p>

        <p>Nous vous conseillons vivement de changer votre mot de passe dans les paramètres de votre compte.</p>
        
        <p>Ce lien est valable pendant <b><?= config('Auth')->magicLinkLifetime / 60 ?> minutes</b>.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="<?= url_to('verify-magic-link') ?>?token=<?= $token ?>" 
               style="background-color: #007bff; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">
               Me connecter à mon compte
            </a>
        </div>
        
        <p>Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email en toute sécurité.</p>
        
        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
        
        <p style="font-size: 12px; color: #777;">
            À bientôt sur <?= config('App')->siteName ?>.
        </p>
    </div>
</body>
</html>