<x-layout>
    <x-slot name="title">
        Rider Human Resources
    </x-slot>


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card card-body">

                    <div class="row">
                        @if (auth()->user()->avatar)
                        <div class="col-lg-4">
                            <img src="{{asset('storage/'.auth()->user()->avatar)}}" class="img-thumbnail" alt="">
                        </div>
                        @endif
                        <div class="col">
                            <table class="table">
                                <tr>
                                    <td>
                                        <i class="fa-solid fa-id-card-clip"></i>
                                        {{auth()->user()->employee_id}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa-solid fa-user-tie"></i>
                                        {{auth()->user()->name}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa-solid fa-envelope"></i>
                                        {{auth()->user()->email}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa-solid fa-briefcase"></i>
                                        {{auth()->user()->department->name}}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <form action="{{route('logout')}}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-danger mt-3">
                            <i class="fa-solid fa-right-from-bracket me-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layout>