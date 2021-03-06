<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use \Datetime;
use function PHPUnit\Framework\isEmpty;


class OthersProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {

        $requestedEmail = json_decode(json_encode(DB::table('users')->where('id', 'LIKE', request()->id)->get()->toArray()), true)[0]['email'];

        $userInfo = json_decode(json_encode(DB::table('users')->where('email', 'LIKE', $requestedEmail)->get()->toArray()), true);

        $isPrivate = $userInfo[0]['private'];

        $friend = $this->isFriend($requestedEmail);

        if ($isPrivate and $friend != "true") {
            return view('othersProfile', ["info" => $userInfo[0], "isFriend" => "false", "private" => true]);
        }

        $posts = DB::table('profiles')->where('email', 'LIKE', $requestedEmail)->orderBy('created_at', 'desc')->get()->toArray();

        $postArr = [];

        for ($i = 0; $i < sizeof($posts); $i++) {
            $postArr[] = json_decode(json_encode($posts[$i]), true);
            if (isset($postArr[$i]['comments'])) {
                $comments = $postArr[$i]['comments'];
                $comments = explode("|||", $comments);

                $commentsArr = [];
                foreach ($comments as $c) {
                    $postComment = explode("||", $c);
                    $commenterName = json_decode(json_encode(DB::table('users')->where('email', 'LIKE', $postComment[0])->first("name")), true)['name'];

                    $profileImage = json_decode(json_encode(DB::table('users')->where('email', 'LIKE', $postComment[0])->first("image")), true)['image'];

                    $postComment[0] = $commenterName;
                    $postComment[3] = $profileImage;
                    $commentsArr[] = $postComment;
                }
                $postArr[$i]['comments'] = $commentsArr;
            }
        }

        return view('othersProfile', ["data" => $postArr, "info" => $userInfo[0], "isFriend" => $friend]);
    }

    public function isFriend($requestedEmail): string
    {
        $email = Auth::user()->email;
        $userFriends = explode("||", json_decode(json_encode(DB::table('users')->where('email', 'LIKE', $email)->get()->toArray()), true)[0]['friends']);
        //if the logged in user has already send a request to the other user
        $userRequests = explode("||", json_decode(json_encode(DB::table('users')->where('email', 'LIKE', $requestedEmail)->get()->toArray()), true)[0]['friendRequests']);
        //if the logged in user has a friend requested from the other user
        $loggedInUserReqs = explode("||", json_decode(json_encode(DB::table('users')->where('email', 'LIKE', $email)->get()->toArray()), true)[0]['friendRequests']);

        $friend = in_array($requestedEmail, $userFriends);
        $requested = in_array($email, $userRequests);
        $accept = in_array($requestedEmail, $loggedInUserReqs);

        if (!$friend and !$requested and !$accept) {
            return "false";
        } elseif ($friend) {
            return "true";
        } else if ($requested) {
            return "requested";
        } else {
            return "accept";
        }
    }

    public function addComment()
    {
        $email = Auth::user()->email;
        $requestedEmail = $_POST['accountEmail'];

        $post = DB::table('profiles')->where('id', 'LIKE', $_POST['id'])->get();

        $comments = json_decode(json_encode($post), true)[0]['comments'];

        $date = new DateTime("now", new \DateTimeZone('America/Halifax'));
        $content = $email . "||" . $_POST['text'] . "||" . $date->format('d-m-Y H-i-s');

        //if there are no comments yet, add a comment of the email of the user and the text of the comment (separated by || for parsing)
        if (!isset($comments)) {
            $comments = $content;
        } else {
            //separate different comments by ||| for parsing and extracting
            $comments = $comments . "|||" . $content;
        }

        DB::table('profiles')->where('id', 'LIKE', $_POST['id'])->update(array('comments' => $comments));

        if (isset($_POST['source'])) {
            return redirect('/requests');
            $userId = json_decode(json_encode(DB::table('users')->where('email', 'LIKE', $requestedEmail)->get()->toArray()), true)[0]['id'];
        }

        return redirect('/newsfeed');
    }

    public function search()
    {
        $matchingUsers = json_decode(json_encode(DB::table('users')->where('name', 'LIKE', "%" . $_POST['search'] . "%")->get()->toArray()), true);

        foreach ($matchingUsers as $user) {
            $req = [];
            $req[0] = $user['name'];
            $req[1] = $user['image'];
            $req[2] = $user['id'];
            $requestsInfo[] = $req;
        }

        return view('searchResults', ['users' => $requestsInfo]);
    }
}
