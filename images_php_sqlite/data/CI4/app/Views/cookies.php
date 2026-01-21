<?php helper('cookie') ?>

<?php if(get_cookie('acceptcookies') == null): ?>

    <div id="cookieBanner">
        <div class="cookie-content">
            <span class="material-icons">cookie</span>
            <p>
                Nous utilisons des cookies pour améliorer votre expérience (panier, connexion).
                En continuant, vous acceptez notre politique de confidentialité.
            </p>
        </div>
        <div class="cookie-actions">
            <button id="denycookies" class="cookie-btn btn-refuse">Refuser</button>
            <button id="acceptcookies" class="cookie-btn btn-accept">Accepter tout</button>
        </div>
    </div>

    <script>
        document.getElementById("acceptcookies").onclick = () => {
            fetch('<?= site_url("cookies/accept") ?>')
                .then(r => r.json())
                .then(() => document.getElementById("cookieBanner").remove())
        }

        document.getElementById("denycookies").onclick = () => {
            fetch('<?= site_url("cookies/decline") ?>')
                .then(r => r.json())
                .then(() => document.getElementById("cookieBanner").remove())
        }
    </script>


    <style>
        #cookieBanner {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #fff;
            color: #333;
            padding: 20px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10000;
            box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.15);
            transition: transform 0.4s ease-in-out;
            flex-wrap: wrap;
            gap: 15px;
        }

        .cookie-content {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
            min-width: 300px;
        }

        .cookie-content .material-icons {
            color: #bb946f;
            font-size: 30px;
        }

        .cookie-actions {
            display: flex;
            gap: 10px;
        }

        .cookie-btn {
            padding: 10px 25px;
            border-radius: 25px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-accept {
            background: #003366;
            color: white;
        }

        .btn-refuse {
            background: #f0f0f0;
            color: #666;
        }

        .cookie-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        @media (max-width: 600px) {
            #cookieBanner {
                flex-direction: column;
                text-align: center;
            }

            .cookie-content {
                flex-direction: column;
            }
        }
    </style>
<?php endif; ?>