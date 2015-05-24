
    <li>
        <div class="panel panel-default" >
            <!-- Default panel contents -->
            <div class="panel-heading" style="font-weight: bold">{$test.question}</div>


            <!-- List group -->
            <ul class="list-group">

                {foreach from=unserialize($test.answers) item=answer}
                <li class="list-group-item">
                    <div class="radio-inline">
                        <label style="font-weight: 400">
                            <input type="radio" name="{$test.id}"  value="{$answer}">
                            {$answer}
                        </label>
                    </div>
                </li>
                {/foreach}
            </ul>
        </div>
    </li>



