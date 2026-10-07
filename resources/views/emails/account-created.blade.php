<div style="font-family: Arial, sans-serif; max-width: 500px; margin: auto;">
    <h2 style="color: #14b8a6;">Fadj-Ma</h2>
    <p>Bonjour {{ $user->name }},</p>
    <p>Votre compte a bien ete cree avec l'adresse <strong>{{ $user->email }}</strong>.</p>
    <p>Voici votre mot de passe temporaire : <strong>{{ $password }}</strong></p>
    <p>Nous vous recommandons de le changer apres votre premiere connexion.</p>
    <p>Merci de votre confiance,<br>L'equipe Fadj-Ma</p>
</div>