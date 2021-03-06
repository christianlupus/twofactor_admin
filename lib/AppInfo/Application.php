<?php

declare(strict_types=1);

/**
 * @author Christoph Wurst <christoph@winzerhof-wurst.at>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\TwoFactorAdmin\AppInfo;

use OCA\TwoFactorAdmin\Event\StateChanged;
use OCA\TwoFactorAdmin\Listener\StateChangeRegistryUpdater;
use OCP\AppFramework\App;

class Application extends App {

	public function __construct(array $urlParams = []) {
		parent::__construct('twofactor_admin', $urlParams);

		$container = $this->getContainer();

		$dispatcher = $container->getServer()->getEventDispatcher();
		$dispatcher->addListener(StateChanged::class, function (StateChanged $event) use ($container) {
			/** @var IListener[] $listeners */
			$listeners = [
				$container->query(StateChangeRegistryUpdater::class),
			];

			foreach ($listeners as $listener) {
				$listener->handle($event);
			}
		});
	}

}
