<!-- <div class="video-background-container zdjecie-baner-glowna">

<section class="about-us-second baner-sekcja">
        <div class="about-us-second-title">
   
            <div class="cta-glowna">    
            <div class="review-card">
        <div class="avatar-group">

            <img src="https://shav.pl/wp-content/uploads/pexels-lucaspezeta-2112715.jpg" alt="Avatar klienta 1" class="avatar-item">
            <img src="https://shav.pl/wp-content/uploads/Gemini_Generated_Image_vngbkgvngbkgvngb.png" alt="Avatar klienta 2" class="avatar-item">
            <img src="https://shav.pl/wp-content/uploads/Gemini_Generated_Image_fejkn5fejkn5fejk.png" alt="Avatar klienta 3" class="avatar-item">
            <img src="https://shav.pl/wp-content/uploads/Gemini_Generated_Image_7ouytv7ouytv7ouy.png" alt="Avatar klienta 4" class="avatar-item">
            <img src="https://shav.pl/wp-content/uploads/Gemini_Generated_Image_d28ohed28ohed28o.png" alt="Avatar klienta 5" class="avatar-item">
        </div>
        <p class="review-text">100K+ zadowolonych klientów</p>
        <div class="rating-section">
            <span class="star">&#9733;</span> 
            <span class="star">&#9733;</span>
            <span class="star">&#9733;</span>
            <span class="star">&#9733;</span>
            <span class="star">&#9733;</span>
            <span class="rating-score">4.9 - świetny</span>
        </div>
      
        <p class="new-promo-text">NAJNIŻSZA CENA W HISTORII</p>
    </div>           
            <a href="<?php echo esc_url(home_url('/produkt/golarka-shav-maszynka-do-miejsc-intymnych/')); ?>" class="cta-button">
             Zobacz teraz
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M0.748537 14C1.00911 12.3211 1.75489 10.7457 2.90138 9.45236C4.04788 8.159 5.54926 7.19936 7.23541 6.68216V9.35158C7.23545 9.45258 7.2648 9.55157 7.32018 9.63748C7.37556 9.7234 7.45479 9.79284 7.54899 9.83803C7.6432 9.88322 7.74866 9.90238 7.85358 9.89337C7.9585 9.88436 8.05873 9.84753 8.14306 9.787L11.0572 7.6939L14.2728 5.38429C14.3434 5.33361 14.4006 5.26787 14.44 5.19229C14.4795 5.11671 14.5 5.03336 14.5 4.94886C14.5 4.86435 14.4795 4.781 14.44 4.70542C14.4006 4.62984 14.3434 4.5641 14.2728 4.51342L11.0572 2.20373L8.14306 0.110602C8.05933 0.048816 7.95898 0.0111903 7.85375 0.00213411C7.74852 -0.00692205 7.64278 0.0129666 7.54892 0.0594691C7.45383 0.103609 7.37384 0.172823 7.31831 0.258998C7.26278 0.345172 7.23403 0.444737 7.23541 0.546022V2.92385C4.91267 3.66808 2.96189 5.21439 1.76281 7.26177C0.563737 9.30916 0.202184 11.7111 0.748537 14Z" fill="#1CC9F2"/>
            </svg>
            </a>
            </div>
        </div>
    </section>
</div>

<style>
        /* Custom styles to fine-tune according to the image */
   

        .review-card {
            background-color: transparent; /* Zmienione na przezroczyste */
            color: #fff; /* White text color */
            padding: 20px 25px; /* Adjust padding to match image more closely */
            border-radius: 12px; /* Slightly more rounded corners */
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            border: none; /* Usunięta ramka */
            box-shadow: none; /* Usunięty cień */
            width: fit-content; /* Make card width fit its content */
            max-width: 90%; /* Ensure responsiveness on smaller screens */
            padding-bottom: 0;
        }

        .avatar-group {
            display: flex;
            margin-bottom: 6px; /* Space between avatars and text */
        }

        .avatar-item {
            width: 48px; /* Slightly larger avatars */
            height: 48px;
            border-radius: 50%;
            border: 3px solid #fff; /* Biała obramówka */
            object-fit: cover;
            object-position: center;
            margin-left: -15px; /* Overlap effect */
            transition: transform 0.2s ease-in-out; /* Smooth hover effect */
        }

        .avatar-item:first-child {
            margin-left: 0; /* First avatar doesn't need negative margin */
        }

        .avatar-item:hover {
            transform: translateY(-5px); /* Slight lift on hover */
        }

        .review-text {
            font-size: 1.15rem; /* Slightly larger font for prominence */
            font-weight: 600; /* Semi-bold */
            margin-bottom: 6px; /* Space between text and stars */
            letter-spacing: -0.02em; /* Tighten letter spacing slightly */
        }

        .rating-section {
            display: flex;
            align-items: center;
            /* margin-bottom: 8px; Zmniejszony margines poniżej sekcji ocen */
        }

        .star {
            font-size: 1.2rem; /* Adjusted font size for star icon */
            /* Kolor gwiazdki będzie zmieniany przez background-clip */
            color: transparent; /* Ustawiamy na transparent, aby gradient był widoczny */
            background-color: #1CC9F2; /* Jasnoniebieskie tło dla gwiazdki */
            padding: 4px 6px; /* Padding, aby tło było widoczne */
            border-radius: 4px; /* Mały border-radius dla zaokrąglonych rogów */
            line-height: 1; /* Wyrównanie tekstu w pionie */
            margin-right: 2px; /* Zmniejszony odstęp między gwiazdkami */
            display: inline-flex; /* Flexbox do centrowania zawartości */
            align-items: center;
            justify-content: center;
            width: 30px; /* Stała szerokość dla spójnego rozmiaru tła */
            height: 30px; /* Stała wysokość dla spójnego rozmiaru tła */
            box-sizing: border-box; /* Padding i border wliczane do całkowitej szerokości/wysokości */
        }

        /* Styl dla wszystkich pełnych gwiazdek (pierwsze cztery) */
        .star:not(:last-of-type) {
            color: #fff; /* Biały kolor dla ikon pełnych gwiazdek */
        }

        /* Styl dla ostatniej gwiazdki, aby gwiazdka była w 90% biała i w 10% czarna */
        .star:last-of-type {
            /* Gradient liniowy dla koloru TEKSTU gwiazdki */
            background: linear-gradient(to right, white 0%, white 90%, black 90%, black 100%);
            -webkit-background-clip: text; /* Wymagane dla niektórych przeglądarek (np. Safari) */
            background-clip: text; /* Nakłada tło na kształt tekstu */
            color: transparent; /* Sprawia, że kolor tekstu jest przezroczysty, pokazując tło */
            /* Tło elementu (.star) pozostaje niebieskie */
            background-color: #1CC9F2;
        }

        .rating-score {
            font-size: 1.15rem; /* Dopasowanie rozmiaru czcionki do review-text */
            font-weight: 500; /* Średnia grubość */
            margin-left: 10px; /* Odstęp między gwiazdkami a tekstem oceny */
            color: #e0e0e0; /* Lekko złamana biel dla tekstu oceny */
        }

        .new-promo-text {
            font-size: 36px; /* Większy rozmiar czcionki */
            color: #fff; /* Biały kolor tekstu */
            text-transform: uppercase; /* Wielkie litery */
            margin-top: 0px; /* Zmniejszony odstęp od góry */
            margin-bottom: 0;
            font-weight: 600;
            /* letter-spacing: 0.05em; Delikatne rozciągnięcie liter dla lepszego wyglądu Bebas Neue */
        }

        .cta-glowna {
            
            flex-direction: column;
            align-items: baseline;
            
        }
        .cta-button {
            padding-left: 25px;
        }

        .baner-sekcja {
            align-items: baseline;
        }

        @media (max-width: 786px) {
  .new-promo-text {
    font-size: 28px;
  }
  .about-us-second-title {
    padding: 0;
  }

}

        /* Responsive adjustments using Tailwind's breakpoints directly in HTML classes */
    </style> -->


    <div class="custom-slider-container">
    <div class="custom-slider-wrapper">
        <div class="custom-slide slide-1">
            <section class="about-us-second baner-sekcja">
                <div class="about-us-second-title">
                    <div class="cta-glowna">
                        <div class="review-card">
                            <div class="avatar-group">
                                <img src="https://shav.pl/wp-content/uploads/pexels-lucaspezeta-2112715.jpg" alt="Avatar klienta 1" class="avatar-item">
                                <img src="https://shav.pl/wp-content/uploads/Gemini_Generated_Image_vngbkgvngbkgvngb.png" alt="Avatar klienta 2" class="avatar-item">
                                <img src="https://shav.pl/wp-content/uploads/Gemini_Generated_Image_fejkn5fejkn5fejk.png" alt="Avatar klienta 3" class="avatar-item">
                                <img src="https://shav.pl/wp-content/uploads/Gemini_Generated_Image_7ouytv7ouytv7ouy.png" alt="Avatar klienta 4" class="avatar-item">
                                <img src="https://shav.pl/wp-content/uploads/Gemini_Generated_Image_d28ohed28ohed28o.png" alt="Avatar klienta 5" class="avatar-item">
                            </div>
                            <p class="review-text">200K+ zadowolonych klientów</p>
                            <div class="rating-section">
                                <span class="star">&#9733;</span>
                                <span class="star">&#9733;</span>
                                <span class="star">&#9733;</span>
                                <span class="star">&#9733;</span>
                                <span class="star">&#9733;</span>
                                <span class="rating-score">4.9 - świetny</span>
                            </div>
                            <p class="new-promo-text">-25% z kodem SPRING25</p>
                        </div>
                        <a href="<?php echo esc_url(home_url('/produkt/golarka-damska-shav-woman/')); ?>" class="cta-button">
                            Zobacz teraz
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.748537 14C1.00911 12.3211 1.75489 10.7457 2.90138 9.45236C4.04788 8.159 5.54926 7.19936 7.23541 6.68216V9.35158C7.23545 9.45258 7.26480 9.55157 7.32018 9.63748C7.37556 9.72340 7.45479 9.79284 7.54899 9.83803C7.64320 9.88322 7.74866 9.90238 7.85358 9.89337C7.95850 9.88436 8.05873 9.84753 8.14306 9.787L11.0572 7.6939L14.2728 5.38429C14.3434 5.33361 14.4006 5.26787 14.4400 5.19229C14.4795 5.11671 14.5000 5.03336 14.5000 4.94886C14.5000 4.86435 14.4795 4.781 14.4400 4.70542C14.4006 4.62984 14.3434 4.56410 14.2728 4.51342L11.0572 2.20373L8.14306 0.110602C8.05933 0.048816 7.95898 0.0111903 7.85375 0.00213411C7.74852 -0.00692205 7.64278 0.0129666 7.54892 0.0594691C7.45383 0.103609 7.37384 0.172823 7.31831 0.258998C7.26278 0.345172 7.23403 0.444737 7.23541 0.546022V2.92385C4.91267 3.66808 2.96189 5.21439 1.76281 7.26177C0.563737 9.30916 0.202184 11.7111 0.748537 14Z" fill="#1CC9F2"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </section>
        </div>

        <div class="custom-slide slide-2">
            <section class="about-us-second baner-sekcja">
                <div class="about-us-second-title">
                    <div class="cta-glowna">
                        <div class="review-card">
                            <div class="avatar-group">
                                <img src="https://shav.pl/wp-content/uploads/Gemini_Generated_Image_vngbkgvngbkgvngb.png" alt="Avatar klienta 2" class="avatar-item">
                                <img src="https://shav.pl/wp-content/uploads/pexels-lucaspezeta-2112715.jpg" alt="Avatar klienta 1" class="avatar-item">
                                <img src="https://shav.pl/wp-content/uploads/Gemini_Generated_Image_fejkn5fejkn5fejk.png" alt="Avatar klienta 3" class="avatar-item">
                                <img src="https://shav.pl/wp-content/uploads/Gemini_Generated_Image_7ouytv7ouytv7ouy.png" alt="Avatar klienta 4" class="avatar-item">
                                <img src="https://shav.pl/wp-content/uploads/Gemini_Generated_Image_d28ohed28ohed28o.png" alt="Avatar klienta 5" class="avatar-item">
                            </div>
                            <p class="review-text">200K+ zadowolonych klientów</p>
                            <div class="rating-section">
                                <span class="star">&#9733;</span>
                                <span class="star">&#9733;</span>
                                <span class="star">&#9733;</span>
                                <span class="star">&#9733;</span>
                                <span class="star">&#9733;</span>
                                <span class="rating-score">4.9 - perfekcyjny</span>
                            </div>
                            <p class="new-promo-text">Gładkość i delikatność dla niej</p>
                        </div>
                        <a href="<?php echo esc_url(home_url('/produkt/golarka-damska-shav-woman/')); ?>" class="cta-button">
                            Odkryj produkty
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.748537 14C1.00911 12.3211 1.75489 10.7457 2.90138 9.45236C4.04788 8.159 5.54926 7.19936 7.23541 6.68216V9.35158C7.23545 9.45258 7.26480 9.55157 7.32018 9.63748C7.37556 9.72340 7.45479 9.79284 7.54899 9.83803C7.64320 9.88322 7.74866 9.90238 7.85358 9.89337C7.95850 9.88436 8.05873 9.84753 8.14306 9.787L11.0572 7.6939L14.2728 5.38429C14.3434 5.33361 14.4006 5.26787 14.4400 5.19229C14.4795 5.11671 14.5000 5.03336 14.5000 4.94886C14.5000 4.86435 14.4795 4.781 14.4400 4.70542C14.4006 4.62984 14.3434 4.56410 14.2728 4.51342L11.0572 2.20373L8.14306 0.110602C8.05933 0.048816 7.95898 0.0111903 7.85375 0.00213411C7.74852 -0.00692205 7.64278 0.0129666 7.54892 0.0594691C7.45383 0.103609 7.37384 0.172823 7.31831 0.258998C7.26278 0.345172 7.23403 0.444737 7.23541 0.546022V2.92385C4.91267 3.66808 2.96189 5.21439 1.76281 7.26177C0.563737 9.30916 0.202184 11.7111 0.748537 14Z" fill="#1CC9F2"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </section>
        </div>

        <div class="custom-slide slide-3">
            <section class="about-us-second baner-sekcja">
                <div class="about-us-second-title">
                    <div class="cta-glowna">
                        <div class="review-card">
                            <div class="avatar-group">
                                <img src="https://shav.pl/wp-content/uploads/Gemini_Generated_Image_d28ohed28ohed28o.png" alt="Avatar klienta 5" class="avatar-item">
                                <img src="https://shav.pl/wp-content/uploads/pexels-lucaspezeta-2112715.jpg" alt="Avatar klienta 1" class="avatar-item">
                                <img src="https://shav.pl/wp-content/uploads/Gemini_Generated_Image_vngbkgvngbkgvngb.png" alt="Avatar klienta 2" class="avatar-item">
                                <img src="https://shav.pl/wp-content/uploads/Gemini_Generated_Image_fejkn5fejkn5fejk.png" alt="Avatar klienta 3" class="avatar-item">
                                <img src="https://shav.pl/wp-content/uploads/Gemini_Generated_Image_7ouytv7ouytv7ouy.png" alt="Avatar klienta 4" class="avatar-item">
                            </div>
                            <p class="review-text">200K+ zadowolonych klientów</p>
                            <div class="rating-section">
                                <span class="star">&#9733;</span>
                                <span class="star">&#9733;</span>
                                <span class="star">&#9733;</span>
                                <span class="star">&#9733;</span>
                                <span class="star">&#9733;</span>
                                <span class="rating-score">4.9 - super oferta</span>
                            </div>
                            <p class="new-promo-text">LEGENDARNY ZŁOTY ZESTAW</p>
                        </div>
                        <a href="<?php echo esc_url(home_url('/zestaw-shav-woman/')); ?>" class="cta-button">
                            Dowiedz się więcej
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.748537 14C1.00911 12.3211 1.75489 10.7457 2.90138 9.45236C4.04788 8.159 5.54926 7.19936 7.23541 6.68216V9.35158C7.23545 9.45258 7.26480 9.55157 7.32018 9.63748C7.37556 9.72340 7.45479 9.79284 7.54899 9.83803C7.64320 9.88322 7.74866 9.90238 7.85358 9.89337C7.95850 9.88436 8.05873 9.84753 8.14306 9.787L11.0572 7.6939L14.2728 5.38429C14.3434 5.33361 14.4006 5.26787 14.4400 5.19229C14.4795 5.11671 14.5000 5.03336 14.5000 4.94886C14.5000 4.86435 14.4795 4.781 14.4400 4.70542C14.4006 4.62984 14.3434 4.56410 14.2728 4.51342L11.0572 2.20373L8.14306 0.110602C8.05933 0.048816 7.95898 0.0111903 7.85375 0.00213411C7.74852 -0.00692205 7.64278 0.0129666 7.54892 0.0594691C7.45383 0.103609 7.37384 0.172823 7.31831 0.258998C7.26278 0.345172 7.23403 0.444737 7.23541 0.546022V2.92385C4.91267 3.66808 2.96189 5.21439 1.76281 7.26177C0.563737 9.30916 0.202184 11.7111 0.748537 14Z" fill="#1CC9F2"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="custom-dotnav">
        <span class="custom-dotnav-item dot-1" role="button" aria-label="Go to slide 1"></span>
        <span class="custom-dotnav-item dot-2" role="button" aria-label="Go to slide 2"></span>
        <span class="custom-dotnav-item dot-3" role="button" aria-label="Go to slide 3"></span>
    </div>

    <button class="custom-nav-button prev-button" aria-label="Previous slide">&#10094;</button>
    <button class="custom-nav-button next-button" aria-label="Next slide">&#10095;</button>
</div>

<style>

/* Główne style kontenera slidera */
.custom-slider-container {
    margin-top: -1px;
    position: relative;
    width: 100%;
    height: 100vh;
    overflow: hidden; /* Nadal ukrywamy to, co poza widokiem */
}

.custom-slider-wrapper {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex; /* Używamy flexbox, aby slajdy były obok siebie */
    transition: transform 1s ease-in-out; /* Dodajemy przejście dla przesuwania całego wrapper'a */
    /* Zaczynamy od transform: translateX(0), a JS to zmieni */
}

.custom-slide {
    flex: 0 0 100%; /* Każdy slajd zajmuje dokładnie 100% szerokości wrappera */
    width: 100%;    /* Zapewniamy, że szerokość jest ustawiona */
    height: 100%;
    /* Usuwamy opacity, visibility i absolute positioning z .custom-slide */
    /* Te właściwości nie są już potrzebne dla efektu przesuwania */
    /* transition: opacity 1s ease-in-out, visibility 1s ease-in-out; */

    display: flex; /* Nadal do centrowania treści */
    align-items: center;
    justify-content: center;
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
    z-index: 1; /* Może być usunięte, jeśli nie ma innych nakładających się elementów */
}



/* .custom-slide.current {
    opacity: 1;
    visibility: visible;
    z-index: 2; 
}

.custom-slide.previous,
.custom-slide.next {
   
} */

/* Tła dla poszczególnych slajdów */
/* https://shav.pl/wp-content/uploads/szachownicanew-2-1.png */
/* https://shav.pl/wp-content/uploads/carbon-glowna.png */
/* https://shav.pl/wp-content/uploads/baner-zloty.png */
.custom-slide.slide-1 {
    background-image: url('http://shavwoman.pl/wp-content/uploads/szachownica-ruda-2-1.png');
}

.custom-slide.slide-2 {
    background-image: url('https://shavwoman.pl/wp-content/uploads/shav-women-2-1.png'); /* Przykładowe inne tło */
}


.custom-slide.slide-3 {
    background-image: url('http://shavwoman.pl/wp-content/uploads/shavwoman-szachownica-2.jpg'); /* Przykładowe inne tło */
}

/* Pozostałe style z Twojej sekcji (te, które już masz) */
.about-us-second {
    height: 100%; /* Sekcja wewnątrz slajdu ma wypełniać całą wysokość slajdu */
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    position: relative; /* Zmień na relative, jeśli nie ma potrzeby absolutnego pozycjonowania */
    overflow: hidden;
    padding: 40px 16px;
    box-sizing: border-box;
    /* Usuń background-image i min-height z .about-us-second, jeśli były */
}

/* ... (resztę Twojego istniejącego CSS, tak jak podałeś, np. dla review-card, avatar-group, star, cta-button itd.) ... */
.review-card {
    background-color: transparent;
    color: #fff;
    padding: 20px 25px;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    border: none;
    box-shadow: none;
    width: fit-content;
    max-width: 90%;
    padding-bottom: 0;
}

.avatar-group {
    display: flex;
    margin-bottom: 6px;
}

.avatar-item {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: 3px solid #fff;
    object-fit: cover;
    object-position: center;
    margin-left: -15px;
    transition: transform 0.2s ease-in-out;
}

.avatar-item:first-child {
    margin-left: 0;
}

.avatar-item:hover {
    transform: translateY(-5px);
}

.review-text {
    font-size: 1.15rem;
    font-weight: 600;
    margin-bottom: 6px;
    letter-spacing: -0.02em;
}

.rating-section {
    display: flex;
    align-items: center;
}

.star {
    font-size: 1.2rem;
    color: transparent;
    background-color: #1CC9F2;
    padding: 4px 6px;
    border-radius: 4px;
    line-height: 1;
    margin-right: 2px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    box-sizing: border-box;
}

.star:not(:last-of-type) {
    color: #fff;
}

.star:last-of-type {
    background: linear-gradient(to right, white 0%, white 90%, black 90%, black 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    background-color: #1CC9F2;
}

.rating-score {
    font-size: 1.15rem;
    font-weight: 500;
    margin-left: 10px;
    color: #e0e0e0;
}

.new-promo-text {
    font-size: 36px;
    color: #fff;
    text-transform: uppercase;
    margin-top: 0px;
    margin-bottom: 0;
    font-weight: 600;
}

.cta-glowna {
    flex-direction: column;
    align-items: baseline;
}

.cta-button {
    padding-left: 25px;
}

.baner-sekcja {
    align-items: baseline;
    max-width: 1300px;
    padding: 0 !important;
}

@media (max-width: 786px) {
    .new-promo-text {
        font-size: 28px;
    }
    .about-us-second-title {
        padding: 0;
    }

    .custom-slider-container {
        height: 80vh;
    }
}


/* Style dla nawigacji kropkowej */
.custom-dotnav {
    position: absolute;
    bottom: 20px; /* Pozycjonowanie na dole */
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px; /* Odstęp między kropkami */
    z-index: 10;
}

.custom-dotnav-item {
    width: 12px;
    height: 12px;
    background-color: rgba(255, 255, 255, 0.5); /* Półprzezroczyste tło nieaktywnych kropek */
    border-radius: 50%;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.custom-dotnav-item.current {
    background-color: #1CC9F2; /* Aktywna kropka */
    /* Zwiększ rozmiar dla aktywnej kropki, jeśli chcesz */
    transform: scale(1.2);
    width: 20px;
    border-radius: 30px;
}

/* Style dla strzałek nawigacyjnych */
.custom-nav-button {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #fff; /* Biały kolor strzałek */
    font-size: 2.5rem;
    cursor: pointer;
    padding: 10px;
    z-index: 9;
    transition: color 0.3s ease;
}

.custom-nav-button:hover {
    color: #1CC9F2; /* Kolor po najechaniu */
}

.prev-button {
    left: 20px;
}

.next-button {
    right: 20px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const sliderWrapper = document.querySelector('.custom-slider-wrapper'); // Pobieramy wrapper
  const slides = document.querySelectorAll('.custom-slide');
  const dots = document.querySelectorAll('.custom-dotnav-item');
  const prevButton = document.querySelector('.prev-button');
  const nextButton = document.querySelector('.next-button');

  let currentIndex = 0;
  const totalSlides = slides.length;
  let autoScrollInterval;
  const autoScrollDelay = 5000; // 5 sekund

  // Funkcja do aktualizacji położenia wrappera i klas dostępności
  function updateSliderPosition() {
    // Obliczamy przesunięcie w pikselach (lub procentach, jeśli preferujesz)
    const offset = -currentIndex * 100; // Przesuwamy o 100% szerokości slajdu
    sliderWrapper.style.transform = `translateX(${offset}%)`;

    // Aktualizacja atrybutów aria-hidden dla dostępności (który slajd jest aktywny)
    slides.forEach((slide, index) => {
      if (index === currentIndex) {
        slide.setAttribute('aria-hidden', 'false');
      } else {
        slide.setAttribute('aria-hidden', 'true');
      }
    });

    // Aktualizacja stanu dotnav (nadal potrzebna)
    dots.forEach((dot, index) => {
      dot.classList.toggle('current', index === currentIndex);
      dot.setAttribute('aria-selected', index === currentIndex ? 'true' : 'false');
      dot.setAttribute('tabindex', index === currentIndex ? '0' : '-1');
    });
  }

  // Funkcja przełączająca slajd
  function goToSlide(index) {
    if (index < 0) {
      currentIndex = totalSlides - 1;
    } else if (index >= totalSlides) {
      currentIndex = 0;
    } else {
      currentIndex = index;
    }
    updateSliderPosition(); // Wywołujemy nową funkcję
  }

  // Obsługa kliknięcia w kropkę
  dots.forEach((dot, index) => {
    dot.addEventListener('click', (e) => {
      e.preventDefault();
      goToSlide(index);
      resetInterval();
    });
  });

  // Obsługa kliknięć przycisków nawigacyjnych
  if (prevButton) {
      prevButton.addEventListener('click', () => {
          goToSlide(currentIndex - 1); // Nie potrzebujemy już % totalSlides tutaj, bo goToSlide to obsługuje
          resetInterval();
      });
  }

  if (nextButton) {
      nextButton.addEventListener('click', () => {
          goToSlide(currentIndex + 1); // Nie potrzebujemy już % totalSlides tutaj
          resetInterval();
      });
  }

  // Przechodzenie do kolejnego slajdu
  function nextSlide() {
    goToSlide(currentIndex + 1);
  }

  // Reset automatycznego przewijania przy interakcji
  function resetInterval() {
    clearInterval(autoScrollInterval);
    autoScrollInterval = setInterval(nextSlide, autoScrollDelay);
  }

  // Inicjalizacja slidera
  updateSliderPosition(); // Wywołaj raz na początku, aby ustawić pierwszy slajd
  resetInterval(); // Uruchomienie interwału po raz pierwszy
});
</script>