<?php

declare(strict_types=1);

/**
 * @author Christian Schrötter <cs@fnx.li>
 * @author Christoph Wurst <christoph@winzerhof-wurst.at>
 * @author André Fondse <andre@hetnetwerk.org>
 *
 * Nextcloud - Two-factor Gateway for Telegram
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\TwoFactorGateway\Service\Gateway\SMS\Provider;

use OCA\TwoFactorGateway\AppInfo\Application;
use OCA\TwoFactorGateway\Exception\ConfigurationException;
use OCP\IConfig;

class ClickatellCentralConfig implements IProviderConfig {
	private const expected = [
		'clickatell_central_api',
		'clickatell_central_user',
		'clickatell_central_password',
	];

	/** @var IConfig */
	private $config;

	public function __construct(IConfig $config) {
		$this->config = $config;
	}

	private function getOrFail(string $key): string {
		$val = $this->config->getAppValue(Application::APP_NAME, $key, null);
		if (is_null($val)) {
			throw new ConfigurationException();
		}
		return $val;
	}

	public function getApi(): string {
		return $this->getOrFail('clickatell_central_api');
	}

	public function setApi(string $api) {
		$this->config->setAppValue(Application::APP_NAME, 'clickatell_central_api', $api);
	}

	public function getUser(): string {
		return $this->getOrFail('clickatell_central_user');
	}

	public function setUser(string $user) {
		$this->config->setAppValue(Application::APP_NAME, 'clickatell_central_user', $user);
	}

	public function getPassword(): string {
		return $this->getOrFail('clickatell_central_password');
	}

	public function setPassword(string $password) {
		$this->config->setAppValue(Application::APP_NAME, 'clickatell_central_password', $password);
	}

	public function isComplete(): bool {
		$set = $this->config->getAppKeys(Application::APP_NAME);
		return count(array_intersect($set, self::expected)) === count(self::expected);
	}

	public function remove() {
		foreach (self::expected as $key) {
			$this->config->deleteAppValue(Application::APP_NAME, $key);
		}
	}
}
