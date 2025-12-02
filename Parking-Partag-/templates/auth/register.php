<!-- <?php include __DIR__ . '/../layouts/header.php'; ?> -->

<h1>Inscription</h1>

<form action="/register" method="POST" style="max-width:400px; margin:auto;">
    
    <div style="margin-bottom:15px;">
        <label for="email">Adresse email</label><br>
        <input 
            type="email" 
            id="email" 
            name="email" 
            required 
            style="width:100%; padding:8px;"
        >
    </div>

    <div style="margin-bottom:15px;">
        <label for="password">Mot de passe</label><br>
        <input 
            type="password" 
            id="password" 
            name="password" 
            required 
            style="width:100%; padding:8px;"
        >
    </div>

    <div style="margin-bottom:20px;">
        <label for="password_confirm">Confirmer le mot de passe</label><br>
        <input 
            type="password" 
            id="password_confirm" 
            name="password_confirm" 
            required 
            style="width:100%; padding:8px;"
        >
    </div>

    <button 
        type="submit" 
        style="padding:10px 20px; width:100%; cursor:pointer;"
    >
        Créer mon compte
    </button>
</form>

<p style="text-align:center; margin-top:15px;">
    Déjà un compte ?  
    <a href="/login">Se connecter</a>
</p>

<!-- <?php include __DIR__ . '/../layouts/footer.php'; ?> -->
