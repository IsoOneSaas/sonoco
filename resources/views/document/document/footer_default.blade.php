<!-- resources/views/document/document/footer_default.blade.php -->
<div class="overflow-x-auto">
    <table id="iso-foot-table" class="table border w-full">
        <tr>
            <td class="align-top">
                <div>
                    @if( isset($document->usersEdit) )
                    <label>Editó:</label>
                    <div class="">
                        @foreach($document->usersEdit as $user)
                        <p class="sign"><img src="{{ url($user->sign) }}" alt="" width="200" /></p>
                        <hr>
                        <p class="name">{{ $user->name }}</p>
                        <p class="job">{{ $user->job }}</p>
                        @endforeach
                    </div>
                    @endif
                </div>                 
            </td>
            <td class="align-top">
                <div>
                    @if( isset($document->usersReview) )
                    <label>Revisó:</label>
                    <div class="">
                        @foreach($document->usersReview as $user)
                        <p class="sign"><img src="{{ url($user->sign) }}" alt="" width="200" /></p>
                        <hr>
                        <p class="name">{{ $user->name }}</p>
                        <p class="job">{{ $user->job }}</p>
                        @endforeach
                    </div>
                    @endif
                </div>                 
            </td>
            <td class="align-top">
                <div>
                    @if( isset($document->usersApprove) )
                    <label>Aprobó:</label>
                    <div>
                        @foreach($document->usersApprove as $user)
                        <p class="sign"><img src="{{ url($user->sign) }}" alt="" width="200" /></p>
                        <hr>
                        <p class="name">{{ $user->name }}</p>
                        <p class="job">{{ $user->job }}</p>
                        @endforeach
                    </div>
                    @endif
                </div>                 
            </td>            
        </tr>
    </table>
</div>  