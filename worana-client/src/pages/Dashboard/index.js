import React, { useEffect, useState } from 'react';

import { Container, Row, Column } from './styles';
import {
  accentColor,
  primaryColorShadow,
  secondaryColor,
  primaryColor,
  dangerColor,
} from '~/components/shared/variables/colors';
import { size } from '~/components/shared/variables/devices';
import useWindowDimensions from '~/services/hooks';

export default function Dashboard() {
  const [isDesktop, setIsDesktop] = useState(false);
  const { width } = useWindowDimensions();
  const { laptop } = size;

  useEffect(() => {
    setIsDesktop(width >= Number(laptop.replace('px', '')));
  }, [laptop, width]);

  return (
    <Container>
      <Row color="white" bold heading bg={secondaryColor}>
        <Column flex={0}>
          <Column>Mês</Column>
          <Column>Novembro</Column>
        </Column>
      </Row>
      <Row>
        <Column>
          <Column>
            <Row color="white" bold bg={primaryColorShadow}>
              <Column>Receitas</Column>
            </Row>
          </Column>
          <Column>
            <Row inverse={!isDesktop} bg="white">
              <Column>Salário</Column>
              <Column>R$ 1200,00</Column>
            </Row>
            <Row inverse={!isDesktop} bg={primaryColor}>
              <Column>Salário</Column>
              <Column>R$ 1200,00</Column>
            </Row>
            <Row inverse={!isDesktop} bg="white">
              <Column>Salário</Column>
              <Column>R$ 1200,00</Column>
            </Row>
          </Column>
        </Column>
        <Column>
          <Column>
            <Row color="white" bold bg={primaryColorShadow}>
              <Column>Investimentos</Column>
            </Row>
          </Column>
          <Column>
            <Row inverse={!isDesktop} bg="white">
              <Column>Ações</Column>
              <Column>R$ 400.000,00</Column>
            </Row>
            <Row inverse={!isDesktop} bg={primaryColor}>
              <Column>Ações</Column>
              <Column>R$ 400.000,00</Column>
            </Row>
            <Row inverse={!isDesktop} bg="white">
              <Column>Ações</Column>
              <Column>R$ 400.000,00</Column>
            </Row>
          </Column>
        </Column>
        <Column>
          <Column>
            <Row color="white" bold bg={dangerColor}>
              <Column>Despesas Fixas</Column>
            </Row>
          </Column>
          <Column>
            <Row inverse={!isDesktop} bg="white">
              <Column>Habitação</Column>
              <Column flex={2}>
                <Row inverse={!isDesktop}>
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop} bg="#DDD">
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop}>
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop} bg="#DDD">
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
              </Column>
            </Row>
            <Row inverse={!isDesktop} bg="grey">
              <Column bold color="#DDD">
                Tranporte
              </Column>
              <Column flex={2}>
                <Row inverse={!isDesktop} color="#DDD">
                  <Column>Carro</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop} bg="#DDD">
                  <Column>Ônibus</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop} color="#DDD">
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop} bg="#DDD">
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
              </Column>
            </Row>
          </Column>
        </Column>
        <Column>
          <Column>
            <Row color="white" bold bg={accentColor}>
              <Column>Despesas Variáveis</Column>
            </Row>
          </Column>
          <Column>
            <Row inverse={!isDesktop} bg="white">
              <Column>Habitação</Column>
              <Column flex={2}>
                <Row inverse={!isDesktop}>
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop} bg="#DDD">
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop}>
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop} bg="#DDD">
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
              </Column>
            </Row>
            <Row inverse={!isDesktop} bg="grey">
              <Column bold color="#DDD">
                Tranporte
              </Column>
              <Column flex={2}>
                <Row inverse={!isDesktop} color="#DDD">
                  <Column>Carro</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop} bg="#DDD">
                  <Column>Ônibus</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop} color="#DDD">
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop} bg="#DDD">
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
              </Column>
            </Row>
          </Column>
        </Column>
        <Column>
          <Column>
            <Row color="white" bold bg={primaryColorShadow}>
              <Column bold>Despesas Adicionais</Column>
            </Row>
          </Column>
          <Column>
            <Row inverse={!isDesktop} bg="white">
              <Column>Habitação</Column>
              <Column flex={2}>
                <Row inverse={!isDesktop}>
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop} bg="#DDD">
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop}>
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop} bg="#DDD">
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
              </Column>
            </Row>
            <Row inverse={!isDesktop} bg="grey">
              <Column bold color="#DDD">
                Tranporte
              </Column>
              <Column flex={2}>
                <Row inverse={!isDesktop} color="#DDD">
                  <Column>Carro</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop} bg="#DDD">
                  <Column>Ônibus</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop} color="#DDD">
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop} bg="#DDD">
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
              </Column>
            </Row>
          </Column>
        </Column>
        <Column>
          <Column>
            <Row color="white" bold bg={dangerColor}>
              <Column bold>Despesas Extras</Column>
            </Row>
          </Column>
          <Column>
            <Row inverse={!isDesktop} bg="white">
              <Column>Habitação</Column>
              <Column flex={2}>
                <Row inverse={!isDesktop}>
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop} bg="#DDD">
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop}>
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop} bg="#DDD">
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
              </Column>
            </Row>
            <Row inverse={!isDesktop} bg="grey">
              <Column bold color="#DDD">
                Tranporte
              </Column>
              <Column flex={2}>
                <Row inverse={!isDesktop} color="#DDD">
                  <Column>Carro</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop} bg="#DDD">
                  <Column>Ônibus</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop} color="#DDD">
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
                <Row inverse={!isDesktop} bg="#DDD">
                  <Column>Condomínio</Column>
                  <Column>R$ 500,00</Column>
                </Row>
              </Column>
            </Row>
          </Column>
        </Column>
        {/* <Column flex={3}>
          <Column>Despesas Fixas</Column>
          <Column>Despesas Variáveis</Column>
          <Column>Despesas Adicionais</Column>
          <Column>Despesas Extras</Column>
        </Column> */}
      </Row>
      {/* <Row bg={accentColor} bold>
        <Column>
          <Column>
            <Column>Categoria</Column>
            <Column>valor</Column>
          </Column>
          <Column>
            <Column>Categoria</Column>
            <Column>valor</Column>
          </Column>
        </Column>
        <Column flex={3}>
          <Column>
            <Column>Categoria</Column>
            <Column>Despesa</Column>
            <Column>valor</Column>
          </Column>
          <Column>
            <Column>Categoria</Column>
            <Column>Despesa</Column>
            <Column>valor</Column>
          </Column>
          <Column>
            <Column>Categoria</Column>
            <Column>Despesa</Column>
            <Column>valor</Column>
          </Column>
          <Column>
            <Column>Categoria</Column>
            <Column>Despesa</Column>
            <Column>valor</Column>
          </Column>
        </Column>
      </Row>
      <Row>
        
        <Column flex={3}>
          <Column>
            <Column>Habitação</Column>
            <Column>Condomínio</Column>
            <Column>R$ 500,00</Column>
          </Column>
          <Column>
            <Column>Habitação</Column>
            <Column>Luz</Column>
            <Column>R$ 500,00</Column>
          </Column>
          <Column>
            <Column>Saúde</Column>
            <Column>Médico</Column>
            <Column>R$ 0,00</Column>
          </Column>
          <Column>
            <Column>Lazer</Column>
            <Column>Viagens</Column>
            <Column>R$ 5.000,00</Column>
          </Column>
        </Column>
      </Row> */}
    </Container>
  );
}
