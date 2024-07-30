<!-- resources/views/components/profile-image.blade.php -->

<div class="profile-image-container">
    @if ($imageUrl)
        <img src="{{ $imageUrl }}" alt="Profile Image" class="profile-image">
    @else
        <p>No image available</p>
    @endif
</div>
