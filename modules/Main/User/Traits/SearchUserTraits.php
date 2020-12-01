<?php


namespace Main\User\Traits;


use Main\User\Http\Resources\RoleResource;
use Main\User\Http\Resources\UserResource;
use Main\User\Model\User;

trait SearchUserTraits
{
    private $request;
    private $users;

    private function filterStatus()
    {

        switch ($this->request->status) {
            case 'active' :
                return $this->users = User::withoutTrashed();
            case 'inactive' :
                return $this->users = User::onlyTrashed();
            default :
                return $this->users = User::withTrashed();
        }

    }

    private function filterTime($filterBy, $filterAs)
    {
        return $this->users = $this->users->whereDate($filterBy, '>=', $filterAs);
    }

    private function createDate($numberDey)
    {
        return now()->subDays((int) $numberDey);
    }

    private function filterCreatedAt()
    {
        $date = $this->createDate($this->request->created_at);
        $this->request->created_at && $this->filterTime('created_at', $date);
    }

    private function filterVerifiedAt()
    {
        $date = $this->createDate($this->request->verified_at);
        $this->request->verified_at && $this->filterTime('verified_at', $date);
    }

    private function query()
    {
        if ($this->request->search){
            $this->users->where(function ($query){
                foreach(User::$searchQuery as $item ){
                    $query->orWhere($item, 'like', '%' . $this->request->search . '%');
                };
            });
        }
    }


    private function startProcess()
    {
        $this->filterStatus();
        $this->filterCreatedAt();
        $this->filterVerifiedAt();
        $this->query();

       return new UserResource($this->users->paginate(env('PAGINATE')));
    }

}
