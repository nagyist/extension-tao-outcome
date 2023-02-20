<?php

/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2023 (original work) Open Assessment Technologies SA;
 */

declare(strict_types=1);

namespace oat\taoResultServer\models\Import\Factory;

use common_exception_MissingParameter;
use common_exception_NotFound;
use common_exception_ResourceNotFound;
use Exception;
use oat\taoDelivery\model\execution\DeliveryExecutionService;
use oat\taoResultServer\models\Import\Input\ImportResultInput;
use Psr\Http\Message\ServerRequestInterface;

class ImportResultInputFactory
{
    private const Q_PARAM_DELIVERY_EXECUTION_ID = 'execution';
    private const Q_PARAM_TRIGGER_AGS_SEND = 'send_ags';

    private DeliveryExecutionService $deliveryExecutionService;

    public function __construct(DeliveryExecutionService $deliveryExecutionService)
    {
        $this->deliveryExecutionService = $deliveryExecutionService;
    }

    /**
     * @throws common_exception_NotFound
     * @throws common_exception_MissingParameter
     * @throws common_exception_ResourceNotFound
     */
    public function createFromRequest(ServerRequestInterface $request): ImportResultInput
    {
        $params = $request->getQueryParams();
        $body = json_decode((string)$request->getBody(), true);

        if (!isset($params[self::Q_PARAM_DELIVERY_EXECUTION_ID])) {
            throw new common_exception_MissingParameter(
                sprintf('Missing %s query param', self::Q_PARAM_DELIVERY_EXECUTION_ID)
            );
        };

        $deliveryExecution = $this->deliveryExecutionService
            ->getDeliveryExecution($params[self::Q_PARAM_DELIVERY_EXECUTION_ID]);

        if (!$deliveryExecution->getFinishTime()) {
            throw new common_exception_ResourceNotFound(
                sprintf(
                    'Finished delivery execution %s not found',
                    $deliveryExecution->getIdentifier()
                )
            );
        }

        $new = new ImportResultInput(
            $params[self::Q_PARAM_DELIVERY_EXECUTION_ID],
            isset($params[self::Q_PARAM_TRIGGER_AGS_SEND]) && $params[self::Q_PARAM_TRIGGER_AGS_SEND] !== false
        );

        foreach ($body as $item) {
            foreach ($item['outcomes'] ?? [] as $outcome) {
                $new->addOutcome($item['itemId'], $outcome['id'], (float)$outcome['value']);
            }
        }

        return $new;
    }
}
