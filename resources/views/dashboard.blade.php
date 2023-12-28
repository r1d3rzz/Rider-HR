<x-layout>
    <x-slot name="title">
        Dashboard
    </x-slot>


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card card-body">
                    You are Loggin

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
