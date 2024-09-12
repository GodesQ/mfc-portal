<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\AnnouncementImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $announcements = array(
            array('title' => 'Ave Maria','content' => '<p>Missionary Families of Christ is part of the Army of Mary and responds to the call of our Mother to fight for her Son. We will celebrate our International Day of Consecration to Our Lady of the Most Holy Rosary on October 7.</p><p><img src="https://static.xx.fbcdn.net/images/emoji.php/v9/tf1/1/16/1f537.png" alt="🔷"> This is in lieu of our October NCR Seniors Teaching Night</p><p><img src="https://static.xx.fbcdn.net/images/emoji.php/v9/tf1/1/16/1f537.png" alt="🔷"> This is open to general membership</p><p><img src="https://static.xx.fbcdn.net/images/emoji.php/v9/tf1/1/16/1f537.png" alt="🔷"> No registration fee</p><p>October 7, 2024</p><p>7:00 PM PHT</p><p>St. Francis of Assisi Parish, Mandaluyong City</p><p>Livestream on Facebook and YouTube</p>','status' => 'shown','user_id' => '1','service_id' => NULL,'section_id' => NULL,'created_at' => '2024-09-12 06:59:55','updated_at' => '2024-09-12 06:59:55'),
            array('title' => '📢 Office Closed on Sept. 12-13 for HO Outing! 🌴
          ','content' => '<p>Our Home Office will be closed on these dates as we take some time to relax and recharge. Regular operations will resume on September 16, Monday. Thank you for your understanding and God bless!</p>','status' => 'shown','user_id' => '1','service_id' => NULL,'section_id' => NULL,'created_at' => '2024-09-11 06:59:55','updated_at' => '2024-09-11 06:59:55'),
            array('title' => 'Gather the sheep, the Shepherd is calling 🐑','content' => '<p>Our Seniors Assembly is coming up, and all our NCR Seniors are required to attend.
          MFC NCR Seniors Assembly
          August 25, 2024
          8:00 AM
          Layforce Multipurpose Hall, San Carlos Seminary, Makati City
          </p>','status' => 'shown','user_id' => '1','service_id' => NULL,'section_id' => NULL,'created_at' => '2024-08-16 06:59:55','updated_at' => '2024-08-16 06:59:55')
          );

          $announcement_images = ["1726124395lX8hDpcAERz1ICUI_maria.jpg", "459263661_534270372299070_7691690719025154347_n.jpg", "seniors_assembly.jpg"];

          foreach ($announcements as $key => $announcement) {
                $announcement = Announcement::create($announcement);
                $nnouncement_image = AnnouncementImage::create(['announcement_id' => $announcement->id, 'image_path' => $announcement_images[$key]]);
          }
    }
}
