@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-5 d-inline">Update Hotel</h5>
                    <form method="POST" action="{{ route('hotels.update', $hotels->id) }}" enctype="multipart/form-data">
                        <!-- Email input -->
                        @csrf
                        <div class="form-outline mb-4 mt-4">
                            <input type="text" value="{{ $hotels->name }}" name="name" id="form2Example1"
                                class="form-control" placeholder="name" />
                        </div>

                        @if ($errors->has('name'))
                            <p class="alert alert-danger">{{ $errors->first('name') }}</p>
                        @endif

                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Description</label>
                            <textarea class="form-control" value="" name="description" id="exampleFormControlTextarea1"
                                placeholder="description" rows="3">{{ $hotels->description }}</textarea>
                        </div>
                        @if ($errors->has('description'))
                            <p class="alert alert-danger">{{ $errors->first('description') }}</p>
                        @endif

                        <div class="form-outline mb-4 mt-4">
                            <label for="exampleFormControlTextarea1">Location</label>

                            <input type="text" value="{{ $hotels->location }}" name="location" id="form2Example1"
                                placeholder="location" class="form-control" />
                        </div>
                        @if ($errors->has('location'))
                            <p class="alert alert-danger">{{ $errors->first('location') }}</p>
                        @endif

                        <!-- Submit button -->
                        <button type="submit" name="submit" class="btn btn-primary  mb-4 text-center">update</button>


                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
