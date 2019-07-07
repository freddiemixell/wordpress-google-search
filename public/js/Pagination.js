import styled from 'styled-components';

const Wrapper = styled.div`
  .current-page {
    background: #333;
  }
`;

const Button = styled.button.attrs(() => ({
  type: "button",
}))``;

function Pagination(props) {
  let nextIndex = null;
  let prevIndex = null;
  let pageButtons = [];
  const { setIndex } = props;
  
  // Finding Next Page Start Index
  if (props.results !== null && typeof props.results.queries.nextPage !== 'undefined') {
    nextIndex = props.results.queries.nextPage[0].startIndex;
  }

  // Finding Prev Page Start Index
  if (props.results !== null && typeof props.results.queries.previousPage !== 'undefined') {
    prevIndex = props.results.queries.previousPage[0].startIndex;
  } else {
    prevIndex = null;
  }

  // Setting Total Results
  if (props.results !== null && typeof props.results.queries.request !== 'undefined') {
    const totalResults = parseInt(props.results.queries.request[0].totalResults);
    let totalPages = Math.round(totalResults / 10);
    totalPages = totalPages > 10 ? 10 : totalPages;

    for (let i = 1; i <= totalPages; i++) {
      let startIndex = 1 + (i - 1) * 10;
      const currentButton = <Button key={i} onClick={() => props.setIndex(startIndex)}>{i}</Button>;

      pageButtons = [...pageButtons, currentButton];
    }
  }

  return (
    <Wrapper>
      <Button disabled={prevIndex === null ? true : false} onClick={() => setIndex(prevIndex)}>&lt;</Button>
      { pageButtons }
      { nextIndex !== null
       ? <Button disabled={nextIndex > 91 ? true : false} onClick={() => setIndex(nextIndex)}>&gt;</Button>
       : null
      }
    </Wrapper>
  );
}

export default Pagination;
