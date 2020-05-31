<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Home</title>
</head>
<body>
  <div class="image-box">
    <div>
      <img src="{{ $profile_image }}" alt="linkedin_profile_image" class="img-rounded">
    </div>
  </div>
  
  <h1>Hello {{ $full_name }}</h1>

  <div>
    <a href="{{ url('/jobs') }}">Jobs</a>
  </div>

  <div>
    <a href="{{ url('/logout') }}">Logout</a>
  </div>
</body>
</html>