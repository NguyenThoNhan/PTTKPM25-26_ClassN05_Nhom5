{{-- Các ván kệ --}}
<div class="shelf" style="bottom: 0px;"></div>
<div class="shelf" style="bottom: 250px;"></div>
<div class="shelf" style="bottom: 500px;"></div>

@php
    $bookIndex = 0;
    $shelfSpacing = 65; // Khoảng cách giữa các sách
    $shelfBottomPositions = [500, 250, 0];
    $booksPerShelf = 9; // Số sách tối đa trên mỗi ván kệ
@endphp

@foreach($shelfBottomPositions as $shelfPosition)
    @for($i = 0; $i < $booksPerShelf; $i++)
        @if(isset($shelfBooks[$bookIndex]))
            @php
                $book = $shelfBooks[$bookIndex];
                
                $bookStyle = "bottom: " . ($shelfPosition + 20) . "px; "
                           . "left: " . (45 + ($i * $shelfSpacing)) . "px; "
                           . "transform: rotateY(" . (int)rand(-3, 3) . "deg);";

                $coverUrl = $book->cover_image_path 
                    ? str_replace('\\', '/', asset('storage/' . $book->cover_image_path))
                    : 'https://via.placeholder.com/200x300.png/333333?text=No+Cover';
                
                $frontFaceStyle = "background-image: url('{$coverUrl}');";
            @endphp

            <a href="{{ route('books.show', $book) }}" target="_blank" title="{{ $book->title }}">
                <div class="book" style="{{ $bookStyle }}">
                    <div class="book-face book-front" style="{{ $frontFaceStyle }}"></div>
                    <div class="book-face book-back"></div>
                    <div class="book-face book-spine">{{ Str::upper(Str::limit($book->title, 20)) }}</div>
                </div>
            </a>
            @php $bookIndex++; @endphp
        @endif
    @endfor
@endforeach