<div class="admin-wrapper">
    <div class="py-4">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="mb-5 jumbotron">
                    <h1 class="display-5 text-dark font-thin m-b-sm">
                        <i class="icon-home mr-2 mb-3"></i> Welcome!
                    </h1>
                    <p class="lead"></p>

                    <p>

                    </p>

                    <p>
                        <strong></strong>
                    </p>

                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <ul class="pl-4 m-0">
                                @if(Route::has('platform.systems.users'))
                                    <li><a href="{{ route('platform.systems.users') }}">Users administration</a></li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <ul class="pl-4 m-0">
                                @if(Route::has('platform.systems.roles'))
                                    <li><a href="{{ route('platform.systems.roles') }}">Roles</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
