<?php

namespace Nhn\Demo\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Nhn\Demo\Actions\Gsheet;
use Nhn\Demo\Actions\Message;
use Nhn\Demo\Actions\Money;
use Webklex\IMAP\Facades\Client;

class UpdateMoneyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:money';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(Gsheet $sheet, Message $cMessage)
    {
        $patterns = [
            // 'Account_type' => '/Loại t&agrave;i khoản <i class="txteng">\/ Account type<\/i><\/td><td align="left">(.+?)<i class="txteng">\/Non-term deposit account<\/i><\/td>/',
            // 'Account' => '/T&agrave;i khoản <i class="txteng">\/ Account<\/i><\/td><td align="left">(\d+)<\/td>/',
            // 'Date' => '/Ng&agrave;y <i class="txteng">\/ Date<\/i><\/td><td align="left">([\d\/\s:]+)<\/td>/',
            'Transaction' => '/Ph&aacute;t sinh <i class="txteng">\/ Transaction<\/i><\/td><td align="left">([\+\-\d\.,\sVND]+)<\/td>/',
            // 'Available_balance' => '/Số dư khả dụng <i class="txteng">\/ Available balance<\/i><\/td><td align="left">([\d\.,\sVND]+)<\/td>/',
            // 'Description' => '/Nội dung <i class="txteng">\/ Description<\/i><\/td><td align="left">(.+?)<\/td>/'
        ];

        $members = $sheet->getMembers(
            config('nhn.members.from'),
            config('nhn.members.to'),
            config('nhn.members.sheet_name'),
        );

        $messages = $cMessage->getMessages();
        /** @var \Webklex\PHPIMAP\Message $message */
        foreach ($messages as $message) {
            $content = $message->getHTMLBody();

            if ($cell = $this->containsMembers($content, $members)) {

                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $content, $matches)) {
                        $value = trim($matches[1]);
                        $this->info("Add $cell = $value to gsheet");

                        // Post to Gsheet
                        $sheet->fill($value, $cell);
                        // Make mail to Seen
                        $message->setFlag('Seen');
                    }
                }
            }
        }
    }

    public function containsMembers($html, $members)
    {
        foreach ($members as $key => $cell) {
            if (strpos(strtolower($html), strtolower($key)) !== false) {
                return $cell;
            }
        }

        return false;
    }
}
