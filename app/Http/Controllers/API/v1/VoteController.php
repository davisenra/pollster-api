<?php

namespace App\Http\Controllers\API\v1;

use App\Exceptions\PollIsExpiredException;
use App\Exceptions\PollNotFoundException;
use App\Exceptions\CannotVoteTwiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVoteRequest;
use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Http\JsonResponse;

class VoteController extends Controller
{
    /**
     * Tries to register a new vote on the referred poll.
     * Throws an error if the client select an invalid poll/option
     * or if the client IP has already voted on the referred poll.
     *
     * @param StoreVoteRequest $request
     * @return JsonResponse
     * @throws PollNotFoundException
     * @throws CannotVoteTwiceException
     * @throws PollIsExpiredException
     */
    public function store(StoreVoteRequest $request): JsonResponse
    {
        $data = $request->validated();
        $voterIp = $request->ip();

        $poll = Poll::where('id', $data['poll'])->first();
        $pollIsExpired = isset($poll->expires_at) && $poll->expires_at < now();

        if (!$poll) {
            throw new PollNotFoundException();
        } elseif ($pollIsExpired) {
            throw new PollIsExpiredException();
        }

        $userAlreadyVoted = $poll->votes->where('voter_ip', $voterIp)->first();

        if ($userAlreadyVoted) {
            throw new CannotVoteTwiceException();
        }

        $option = $poll->options->where('id', $data['option'])->first();

        if (!$option) {
            return new JsonResponse(['message' => 'Invalid option'], 404);
        }

        $vote = new Vote(['voter_ip' => $voterIp]);
        $vote->poll()->associate($poll);
        $vote->pollOption()->associate($option);
        $vote->save();

        return new JsonResponse([
            'message' => 'Vote registered successfully'
        ], 201);
    }
}
