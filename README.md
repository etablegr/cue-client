PHP client for [speix/cue](https://github.com/speix/cue)

Usage: 

    $client = new Cue\Client('127.0.0.1', 8000);
    $task   = new \Cue\Task(
        'queue', // queue name
        'task', // task name
        [
            'message 1',
            'message 2',
            'message 3',
        ],
        60 // delay in seconds
    );
    
    $client->queueTask($task);