function popup(msg)
{
    alert(msg);
}


// userPref_settings.php & env_settings.php
function showValue(str,newValue)
{
    document.getElementById(str).innerHTML=newValue;
}


// env_settings.php lock/unlock
function _un_lock_img(img)
{
	if(img.src.match(/unlock/))
    {
        img.src="/Images/lock.png";
    }
	else
    {
        img.src="/Images/unlock.png";
    }
}