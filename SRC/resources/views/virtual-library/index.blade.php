<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thư Viện Ảo - BookHaven</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Playfair+Display:700|Figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        /* --- Reset & Biến Toàn Cục --- */
        :root {
            --aisle-width: 1200px;
            --book-width: 40px;
            --book-height: 240px;
            --book-depth: 30px;
        }
        body { margin: 0; font-family: 'Figtree', sans-serif; background-color: #0d1117; color: #9ca3af; overflow: hidden; }

        /* --- Không Gian 3D --- */
        .scene { width: 100vw; height: 100vh; perspective: 1500px; }
        .aisle {
            width: 100%; height: 100%; position: relative;
            transform-style: preserve-3d;
            transition: transform 1.2s cubic-bezier(0.25, 1, 0.5, 1);
            transform: translateY(50px) rotateX(-10deg) rotateY(20deg); /* Góc nhìn ban đầu, hơi nghiêng về kệ bên trái */
        }

        /* --- Sàn nhà --- */
        .floor {
            position: absolute; top: 50%; left: 50%;
            width: var(--aisle-width); height: 2000px;
            background-image: url('https://www.transparenttextures.com/patterns/wood-pattern.png');
            background-color: #5a4a3a;
            transform: translateX(-50%) rotateX(90deg);
            box-shadow: inset 0 0 200px rgba(0,0,0,0.6);
        }

        /* --- Kệ Sách --- */
        .bookshelf {
            position: absolute; top: 50%; left: 50%;
            width: 900px; height: 750px; margin-top: -375px; margin-left: -450px;
            transform-style: preserve-3d;
            background-color: rgba(42, 34, 29, 0.7);
            border: 25px solid #4a3424;
            border-radius: 5px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.7);
        }
        .shelf { position: absolute; width: 100%; height: 20px; background-color: #4a3424; left: 0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.5); }
        .bookshelf-left { transform: translateZ(calc(var(--aisle-width) / -2)); }
        .bookshelf-right { transform: rotateY(180deg) translateZ(calc(var(--aisle-width) / -2)); }
        
        /* --- Sách 3D --- */
        .book { position: absolute; width: var(--book-width); height: var(--book-height); transform-style: preserve-3d; transition: transform 0.4s ease; cursor: pointer; }
        .book:hover { transform: translateZ(30px) scale(1.05) rotateY(0deg) !important; z-index: 10; box-shadow: 0 0 30px rgba(255, 223, 186, 0.5); }
        .book-face { position: absolute; width: 100%; height: 100%; backface-visibility: hidden; }
        .book-front { background-color: #fff; background-size: cover; background-position: center; transform: translateZ(calc(var(--book-depth) / 2)); }
        .book-back { background-color: #e0e0e0; transform: rotateY(180deg) translateZ(calc(var(--book-depth) / 2)); }
        .book-spine { width: var(--book-depth); height: 100%; position: absolute; left: calc((var(--book-width) - var(--book-depth)) / 2); background-color: #372e29; transform: rotateY(-90deg); color: #e5d8b1; writing-mode: vertical-rl; text-orientation: mixed; text-align: center; font-size: 11px; font-weight: 600; overflow: hidden; white-space: nowrap; padding-top: 15px; border: 1px solid #2a221d; }
        
        /* --- Giao diện người dùng --- */
        .ui-overlay { position: fixed; inset: 0; pointer-events: none; display: flex; flex-direction: column; justify-content: space-between; align-items: center; padding: 30px; }
        .header-ui { width: 100%; display: flex; justify-content: space-between; align-items: flex-start; }
        .title-container { text-align: center; color: white; text-shadow: 0 2px 10px black; }
        .title-container h1 { font-family: 'Playfair Display', serif; font-size: 3.5rem; margin: 0; }
        .title-container p { opacity: 0.8; margin-top: 0.5rem; }
        .back-home { pointer-events: auto; background-color: rgba(255,255,255,0.1); color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-size: 14px; backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.2); }
        .navigation { pointer-events: auto; }
        .nav-btn { background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.2); color: white; font-size: 24px; width: 60px; height: 60px; border-radius: 50%; margin: 0 10px; cursor: pointer; transition: all 0.3s; backdrop-filter: blur(5px); }
        .nav-btn:hover { background: rgba(255,255,255,0.2); transform: scale(1.1); }
    </style>
</head>
<body>
    @php
        // Chia danh sách sách thành 2 nửa cho 2 kệ
        $shelves = $books->split(2);
        $leftShelfBooks = $shelves->get(0, collect());
        $rightShelfBooks = $shelves->get(1, collect());
    @endphp

    <div class="scene">
        <div id="aisle" class="aisle">
            <div class="floor"></div>
            
            <!-- Kệ sách bên trái -->
            <div class="bookshelf bookshelf-left">
                @include('virtual-library.partials.shelf-content', ['shelfBooks' => $leftShelfBooks])
            </div>
            
            <!-- Kệ sách bên phải -->
            <div class="bookshelf bookshelf-right">
                @include('virtual-library.partials.shelf-content', ['shelfBooks' => $rightShelfBooks])
            </div>
        </div>
    </div>
    
    <div class="ui-overlay">
        <div class="header-ui">
            <a href="{{ route('home') }}" class="back-home">Về Trang Chủ</a>
            <div class="title-container">
                <h1>Thư Viện Ảo</h1>
                <p>Sử dụng ← → để khám phá các kệ sách.</p>
            </div>
            <div></div> <!-- Div trống để căn giữa tiêu đề -->
        </div>
        <div class="navigation">
            <button class="nav-btn" id="rotate-left-btn" title="Xem kệ sách bên trái">←</button>
            <button class="nav-btn" id="rotate-right-btn" title="Xem kệ sách bên phải">→</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const aisle = document.getElementById('aisle');
            const rotateLeftBtn = document.getElementById('rotate-left-btn');
            const rotateRightBtn = document.getElementById('rotate-right-btn');
            
            const leftViewAngle = 20;  // Góc nhìn kệ bên trái
            const rightViewAngle = -20; // Góc nhìn kệ bên phải

            function rotateAisle(angle) {
                aisle.style.transform = `translateY(50px) rotateX(-10deg) rotateY(${angle}deg)`;
            }

            rotateLeftBtn.addEventListener('click', () => rotateAisle(leftViewAngle));
            rotateRightBtn.addEventListener('click', () => rotateAisle(rightViewAngle));

            document.addEventListener('keydown', (event) => {
                if (event.key === 'ArrowLeft') rotateAisle(leftViewAngle);
                if (event.key === 'ArrowRight') rotateAisle(rightViewAngle);
            });
        });
    </script>
</body>
</html>