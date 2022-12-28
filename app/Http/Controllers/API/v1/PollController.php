<?php

namespace App\Http\Controllers\API\v1;

use App\Exceptions\PollNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePollRequest;
use App\Http\Resources\PollResource;
use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class PollController extends Controller
{
    /**
     * Returns a paginated poll collection containing 20 polls/page.
     *
     * @return ResourceCollection
     */
    public function index(): ResourceCollection
    {
        return PollResource::collection(Poll::paginate(20));
    }

    /**
     * Returns the requested poll along with poll options and vote count or
     * throws an exception if no poll is found.
     *
     * @param Request $request
     * @return PollResource
     * @throws PollNotFoundException
     */
    public function show(Request $request): PollResource
    {
        try {
            $poll = Poll::findOrFail($request->id);
            $poll->loadMissing('options');
            $poll->loadCount('votes');
        } catch (ModelNotFoundException) {
            throw new PollNotFoundException();
        }

        return new PollResource($poll);
    }

    /**
     * Stores a new poll with provided poll options and returns the created resource.
     *
     * @param StorePollRequest $request
     * @return PollResource
     */
    public function store(StorePollRequest $request): PollResource
    {
        $data = $request->validated();

        $poll = DB::transaction(function () use ($data) {
            $poll = new Poll([
                'title' => $data['title'],
                'email' => $data['email'] ?? null,
                'expires_at' => $data['expires_at'] ?? null
            ]);
            $poll->save();

            foreach ($data['options'] as $option) {
                $pollOption = new PollOption([
                    'option' => $option
                ]);

                $poll->options()->save($pollOption);
            }

            $poll->loadMissing('options');
            $poll->loadCount('votes');

            return $poll;
        });

        return new PollResource($poll);
    }

    /**
     * Deletes referred poll along with poll options or throws an exception
     * if the poll is not found.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws PollNotFoundException
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            $poll = Poll::findOrFail($request->id);
            $poll->delete();
        } catch (ModelNotFoundException) {
            throw new PollNotFoundException();
        }

        return new JsonResponse([
            'message' => 'Poll deleted successfully'
        ], 200);
    }
}
